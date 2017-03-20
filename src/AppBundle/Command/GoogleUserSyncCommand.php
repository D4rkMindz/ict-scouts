<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class GoogleUserSyncCommand.
 */
class GoogleUserSyncCommand extends ContainerAwareCommand
{
    /**
     * Configures command.
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('app:google:usersync')
             ->setDescription('Syncs users with the G Suite.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \LogicException
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->title('Google Usersync');

        $googleUserService = $this->getContainer()->get('app.service.google.user');
        $googleUserService->getAllUsers($this->getContainer()->getParameter('google_apps_domain'));

        $symfonyStyle->writeln('Done');
    }
}
