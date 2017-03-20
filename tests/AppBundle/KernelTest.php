<?php

namespace Tests\AppBundle;

use AppBundle\Entity\Person;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class KernelTest.
 *
 * @coversNothing 
 */
class KernelTest extends WebTestCase
{
    /** @var Client */
    protected $client = null;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var Application
     */
    private $application;

    /**
     * Prepares environment for tests.
     */
    public function setup()
    {
        self::bootKernel();
        $this->application = new \Symfony\Bundle\FrameworkBundle\Console\Application(self::$kernel);
        $this->application->setAutoExit(false);

        $this->runConsole('doctrine:schema:drop', ['--force' => true]);
        $this->runConsole('doctrine:schema:create');
        $this->runConsole('doctrine:fixtures:load');

        $this->container = self::$kernel->getContainer();
    }

    /**
     * Loads fixtures into test database.
     *
     * @param       $command
     * @param array $options
     *
     * @return mixed
     */
    protected function runConsole($command, array $options = [])
    {
        $options['-e'] = 'test';
        $options['-q'] = null;
        $options = array_merge($options, ['command' => $command]);

        return $this->application->run(new ArrayInput($options));
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Dummy test.
     */
    public function testDummy()
    {
        $this->assertTrue(true);
    }

    protected function logIn($role)
    {
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $group = $entityManager->getRepository('AppBundle:Group')->findOneBy(['role' => $role]);
        $firewall = 'main';
        $session = $this->getContainer()->get('session');

        /** @var Person $person */
        $person = new Person('Doe', 'John');
        $entityManager->persist($person);

        /** @var User $user */
        $user = new User($person,'123456789', 'john.doe@'.$this->getContainer()->getParameter('google_apps_domain'), 'abc123cba');
        $user->setAccessTokenExpire((new \DateTime())->add(new \DateInterval('PT3595S')));
        $user->addGroup($group);

        $entityManager->persist($user);
        $entityManager->flush();

        $token = new UsernamePasswordToken($user->getUsername(), ['accessToken' => 'abc123cba'], $firewall, ['ROLE_ADMIN']);
        $session->set('_security_'.$firewall, serialize($token));
        $session->set('access_token', 'abc123cba');
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
