<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Google_Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class GoogleService.
 */
class GoogleService
{
    const USER = 'user';
    const SERVICE = 'service';

    /**
     * @var array
     */
    private $userScopes = [
        \Google_Service_Oauth2::USERINFO_PROFILE,
        \Google_Service_Oauth2::USERINFO_EMAIL,
    ];

    /**
     * @var array
     */
    private $serviceScopes = [
        \Google_Service_Directory::ADMIN_DIRECTORY_USER_READONLY,
        \Google_Service_Directory::ADMIN_DIRECTORY_GROUP_READONLY,
    ];

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Google_Client
     */
    private $client;

    /**
     * @var ParameterBagInterface
     */
    private $parameters;

    /**
     * GoogleService constructor.
     *
     * @param Kernel        $kernel
     * @param EntityManager $entityManager
     */
    public function __construct(Kernel $kernel, EntityManager $entityManager)
    {
        if (!getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
            putenv(
                'GOOGLE_APPLICATION_CREDENTIALS='.realpath($kernel->locateResource(
                    '@AppBundle/Resources/config/client_secret.json'
                ))
            );
        }

        $this->parameters = $kernel->getContainer()->getParameterBag();
        $this->adminMail = $this->parameters->get('google_apps_admin');
        $this->em = $entityManager;
        $this->client = new Google_Client();
    }

    /**
     * Set Authentication parameters for any API request.
     *
     * @param string $type GoogleService::USER oder GoogleService::SERVICE
     *
     * @throws \Exception
     *
     * @return Google_Client
     */
    public function auth(string $type): Google_Client
    {
        if (self::USER != $type && self::SERVICE != $type) {
            throw new \Exception('Connection type must be "'.self::USER.'" or "'.self::SERVICE.'".');
        }

        switch ($type) {
            case self::SERVICE:
                $this->client->useApplicationDefaultCredentials();
                $this->setScope(self::SERVICE);
                break;
            case self::USER:
                $this->client->setApplicationName($this->parameters->get('google_app_name'));
                $this->client->setClientId($this->parameters->get('google_client_id'));
                $this->client->setClientSecret($this->parameters->get('google_client_secret'));
                $this->client->setRedirectUri($this->parameters->get('google_redirect_uri'));
                $this->client->setDeveloperKey($this->parameters->get('google_developer_key'));
                $this->setScope(self::USER);
                break;
        }

        return $this->client;
    }

    /**
     * Get the user scopes.
     *
     * @return array
     */
    public function getUserScopes(): array
    {
        return $this->userScopes;
    }

    /**
     * Get the service scopes.
     *
     * @return array
     */
    public function getServiceScopes(): array
    {
        return $this->serviceScopes;
    }

    /**
     * @return Google_Client
     */
    public function getClient(): Google_Client
    {
        return $this->client;
    }

    /**
     * Initialize the client.
     *
     * @param string $scope
     *
     * @return Google_Client
     */
    public function setScope($scope): Google_Client
    {
        switch ($scope) {
            case self::SERVICE:
                $this->client->addScope($this->getServiceScopes());
                break;
            default:
                $this->client->addScope($this->getUserScopes());
                break;
        }

        return $this->client;
    }

    /**
     * Get the G Suite admin user.
     *
     * @return string
     */
    public function getAdminUser(): string
    {
        return $this->adminMail;
    }

    /**
     * Get all users from provided domain.
     *
     * @param $domain
     */
    public function getAllUsers($domain)
    {
        $this->auth(self::SERVICE);
        $this->client->setSubject($this->getAdminUser());

        $service = new \Google_Service_Directory($this->client);

        $users = $service->users->listUsers(['domain' => $domain])->getUsers();

        $googleUsers = [];
        /** @var \Google_Service_Directory_User $user */
        foreach ($users as $user) {
            $myUser = new \Google_Service_Oauth2_Userinfoplus();
            $myUser->setEmail($user->getPrimaryEmail());
            $myUser->setId($user->getId());

            $dbUser = $this->createUser($myUser);

            $this->updateUserGroups($dbUser, $user->getOrgUnitPath());

            $googleUsers[] = $dbUser;
        }

        $users = $this->em->getRepository('AppBundle:User')->findAll();
        /** @var User $user */
        foreach ($users as $user) {
            if (!in_array($user, $googleUsers)) {
                $this->em->remove($user);
            }
        }
        $this->em->flush();
    }

    /**
     * Create user based on users in GSuite.
     *
     * @param \Google_Service_Oauth2_Userinfoplus $userData
     *
     * @return User
     */
    public function createUser(\Google_Service_Oauth2_Userinfoplus $userData): User
    {
        /** @var User $user */
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['googleId' => $userData->getId()]);

        if (!$user) {
            $user = new User();
            $user->setGoogleId($userData->getId());
            $user->setEmail($userData->getEmail());

            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }

    /**
     * Set group based on Google organisation unit.
     *
     * @param User   $user
     * @param string $ou
     */
    public function updateUserGroups(User &$user, $ou)
    {
        $group = null;
        $userGroups = (!$user->getGroups() ? [] : $user->getGroups());

        $adminGroup = $this->em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_ADMIN']);
        $scoutGroup = $this->em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_SCOUT']);
        $talentGroup = $this->em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_TALENT']);

        if ('/Support' == $ou && !in_array($adminGroup, $userGroups)) {
            $group = $adminGroup;
        } elseif ('/Scouts' == $ou && !in_array($scoutGroup, $userGroups)) {
            $group = $scoutGroup;
        } elseif ('/ict-campus/ICT Talents' == $ou && !in_array($talentGroup, $userGroups)) {
            $group = $talentGroup;
        }

        if ($group) {
            $user->addGroup($group);
            $this->em->persist($user);
        }
    }

    /**
     * Update user data.
     *
     * @param int   $googleId
     * @param array $accessToken =false
     *
     * @return bool
     */
    public function updateUserAccessToken($googleId, $accessToken): bool
    {
        /** @var User $user */
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['googleId' => $googleId]);

        if ($user) {
            if ($accessToken) {
                $user->setAccessToken($accessToken['access_token']);
                $user->setAccessTokenExpireDate(
                    (new \DateTime())->add(new \DateInterval('PT'.($accessToken['expires_in'] - 5).'S'))
                );
            }

            $this->em->persist($user);
            $this->em->flush();

            return true;
        } else {
            return false;
        }
    }
}
