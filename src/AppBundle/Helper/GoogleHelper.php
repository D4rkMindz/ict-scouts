<?php

namespace AppBundle\Helper;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Google_Client;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class GoogleHelper.
 */
class GoogleHelper
{
    const SCOPE_USER = 'user';
    const SCOPE_SERVICE = 'service';

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
     * GoogleHelper constructor.
     *
     * @param Kernel        $kernel
     * @param string        $googleAdminMail
     * @param EntityManager $entityManager
     */
    public function __construct(Kernel $kernel, string $googleAdminMail, EntityManager $entityManager)
    {
        if (!getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
            putenv(
                'GOOGLE_APPLICATION_CREDENTIALS='.realpath($kernel->locateResource(
                    '@AppBundle/Resources/config/client_secret.json'
                ))
            );
        }

        $this->adminMail = $googleAdminMail;
        $this->em = $entityManager;
        $this->client = new \Google_Client();
        $this->client->useApplicationDefaultCredentials();
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
    public function setScope($scope)
    {
        switch ($scope) {
            case self::SCOPE_SERVICE:
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
     *
     * @return array
     */
    public function getAllUsers($domain): array
    {
        $this->setScope(self::SCOPE_SERVICE);
        $this->client->setSubject($this->getAdminUser());

        $service = new \Google_Service_Directory($this->client);

        $users = $service->users->listUsers(['domain' => $domain])->getUsers();

        $returnUsers = [];
        /** @var \Google_Service_Directory_User $user */
        foreach ($users as $user) {
            $name = $user->getName();

            $myUser = new \Google_Service_Oauth2_Userinfoplus();
            $myUser->setEmail($user->getPrimaryEmail());
            $myUser->setFamilyName($name->getFamilyName());
            $myUser->setGivenName($name->getGivenName());
            $myUser->setId($user->getId());

            if ($user->getPrimaryEmail() == $this->getAdminUser()) {
                $this->updateUserData($myUser, ['access_token' => 'abc123cba', 'expires_in' => '3600']);
            } else {
                $this->updateUserData($myUser);
            }

            $returnUsers[] = $myUser;
        }

        return $returnUsers;
    }

    /**
     * Update user data.
     *
     * @param \Google_Service_Oauth2_Userinfoplus $userData
     * @param array                               $accessToken =false
     */
    public function updateUserData(\Google_Service_Oauth2_Userinfoplus $userData, $accessToken = null)
    {
        /** @var User $user */
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['googleId' => $userData->getId()]);

        if ($user) {
            if ($accessToken) {
                $user->setAccessToken($accessToken['access_token']);
                $user->setAccessTokenExpireDate(
                    (new \DateTime())->add(new \DateInterval('PT'.($accessToken['expires_in'] - 5).'S'))
                );
            }
            $user->setGivenName($userData->getGivenName());
            $user->setFamilyName($userData->getFamilyName());
            $user->setEmail($userData->getEmail());
        } else {
            $user = new User();
            $user->setGoogleId($userData->getId());
            $user->setGivenName($userData->getGivenName());
            $user->setFamilyName($userData->getFamilyName());
            $user->setEmail($userData->getEmail());
            if ($accessToken) {
                $user->setAccessToken($accessToken['access_token']);
                $user->setAccessTokenExpireDate(
                    (new \DateTime())->add(new \DateInterval('PT'.($accessToken['expires_in'] - 5).'S'))
                );
            }
        }
        $this->em->persist($user);
        $this->em->flush();
    }
}
