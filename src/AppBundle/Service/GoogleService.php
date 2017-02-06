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
}
