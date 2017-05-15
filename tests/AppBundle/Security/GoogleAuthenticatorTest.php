<?php

namespace Tests\AppBundle\Security;

use AppBundle\Security\GoogleAuthenticator;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Tests\AppBundle\KernelTest;

/**
 * Class GoogleAuthenticatorTest.
 *
 *
 * @covers \AppBundle\Security\GoogleAuthenticator
 */
class GoogleAuthenticatorTest extends KernelTest
{
    /** @var GoogleAuthenticator */
    private $authenticator = null;

    public function setUp()
    {
        parent::setup();
        $this->client = static::createClient();

        $this->authenticator = new GoogleAuthenticator();
    }

    public function testFailingLogin()
    {
        $request = new Request();

        $this->assertNull($this->authenticator->getCredentials($request));
    }

    public function testOnAuthenticationFailure()
    {
        $this->failingLogIn();

        $this->client->request('GET', '/admin/');

        $this->assertFalse($this->authenticator->supportsRememberMe());
    }

    public function testStart()
    {
        $this->authenticator->start(new Request());

        $this->assertFalse($this->authenticator->supportsRememberMe());
    }

    public function testSupportsRememberMe()
    {
        $this->logIn('ROLE_ADMIN');

        $this->client->request('GET', '/');

        $this->assertFalse($this->authenticator->supportsRememberMe());
    }

    private function failingLogIn()
    {
        $session = $this->getContainer()->get('session');

        $session->set('access_token', 'cba123abc');
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
