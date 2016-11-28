<?php

namespace AppBundle\Helper;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Google_Client;
use HappyR\Google\ApiBundle\Services\GoogleClient;

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
     * @var GoogleClient
     */
    private $apiClient;

    /**
     * GoogleHelper constructor.
     *
     * @param string        $googleAdminMail
     * @param EntityManager $entityManager
     * @param GoogleClient  $apiClient
     */
    public function __construct(string $googleAdminMail, EntityManager $entityManager, GoogleClient $apiClient)
    {
        $this->adminMail = $googleAdminMail;
        $this->em        = $entityManager;
        $this->apiClient = $apiClient;
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
     * Initialize the client.
     *
     * @param string $scope
     * @param bool   $google if true the pure google client is initialized
     *
     * @return Google_Client|\HappyR\Google\ApiBundle\Services\GoogleClient|object
     */
    public function initClient($scope, $google = false)
    {
        switch ($scope) {
            case self::SCOPE_SERVICE:
                $scope = $this->getServiceScopes();
                break;
            default:
                $scope = $this->getUserScopes();
                break;
        }

        if ($google) { // @TODO: Schau mal an obs das noch braucht.
            $return = new Google_Client();
            $return->addScope($scope);
        } else {
            $this->apiClient->getGoogleClient()->addScope($scope);
            $return = $this->apiClient;
        }

        return $return;
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
        if (!getenv('GOOGLE_APPLICATION_CREDENTIALS')) { // @TODO: Das müssen wir noch überschreiben.
            putenv(
                'GOOGLE_APPLICATION_CREDENTIALS='.$this->container->get('kernel')->locateResource(
                    '@AppBundle/Resources/config/client_secret.json'
                )
            );
        }

        $client = $this->initClient(self::SCOPE_SERVICE, true);
        $client->useApplicationDefaultCredentials();
        $client->setSubject($this->getAdminUser());

        $service = new \Google_Service_Directory($client);

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
    public function updateUserData(\Google_Service_Oauth2_Userinfoplus $userData, $accessToken = null): void
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
