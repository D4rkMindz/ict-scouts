<?php
/**
 * Created by PhpStorm.
 * User: ashura
 * Date: 23.12.16
 * Time: 07:21
 */

namespace Tests\AppBundle;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Class KernelTest.
 */
class KernelTest extends WebTestCase
{
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

        $this->runConsole("doctrine:schema:drop", ["--force" => true]);
        $this->runConsole("doctrine:schema:create");
        $this->runConsole("doctrine:fixtures:load");

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
    protected function runConsole($command, Array $options = [])
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options       = array_merge($options, ['command' => $command]);

        return $this->application->run(new ArrayInput($options));
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}