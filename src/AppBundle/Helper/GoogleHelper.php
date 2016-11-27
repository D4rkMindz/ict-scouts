<?php

namespace AppBundle\Helper;

use AppBundle\Helper\Base\BaseHelper;
use Google_Client;

class GoogleHelper extends BaseHelper
{
    private $userScopes = [
        \Google_Service_Oauth2::USERINFO_PROFILE,
        \Google_Service_Oauth2::USERINFO_EMAIL,
    ];

    private $serviceScopes = [
        \Google_Service_Directory::ADMIN_DIRECTORY_USER_READONLY,
        \Google_Service_Directory::ADMIN_DIRECTORY_GROUP_READONLY,
    ];

    /**
     * Get the user scopes.
     *
     * @return array
     */
    public function getUserScopes()
    {
        return $this->userScopes;
    }

    /**
     * Get the service scopes.
     *
     * @return array
     */
    public function getServiceScopes()
    {
        return $this->serviceScopes;
    }

    /**
     * Initialize the client.
     *
     * @param bool $google if true the pure google client is initialized
     *
     * @return Google_Client|\HappyR\Google\ApiBundle\Services\GoogleClient|object
     */
    public function initClient($google = false)
    {
        if ($google) {
            $return = new Google_Client();
        } else {
            $return = $this->container->get('happyr.google.api.client');
        }

        return $return;
    }

    /**
     * Get the G Suite admin user.
     *
     * @return string
     */
    public function getAdminUser()
    {
        return $this->container->getParameter('google_apps_admin');
    }

    /**
     * Get all users from provided domain.
     *
     * @param $domain
     *
     * @return array
     */
    public function getAllUsers($domain)
    {
        if (!getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
            putenv('GOOGLE_APPLICATION_CREDENTIALS='.$this->container->get('kernel')->locateResource('@AppBundle/Resources/config/client_secret.json'));
        }

        /** @var GoogleHelper $googleHelper */
        $googleHelper = $this->container->get('app.helper.google');

        /** @var Google_Client $client */
        $client = $googleHelper->initClient(true);
        $client->useApplicationDefaultCredentials();
        $client->setSubject($googleHelper->getAdminUser());
        $client->setScopes($googleHelper->getServiceScopes());

        $service = new \Google_Service_Directory($client);

        $users = $service->users->listUsers(['domain' => $domain])->getUsers();

        $returnUsers = [];
        /** @var \Google_Service_Directory_User $user */
        foreach ($users as $user) {
            /** @var \Google_Service_Directory_UserName $name */
            $name = $user->getName();
            $returnUsers[] = [
                'name' => $name->getFullName(),
                'mail' => $user->getPrimaryEmail(),
            ];
        }
        return $returnUsers;
    }
}
