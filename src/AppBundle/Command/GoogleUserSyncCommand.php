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
     */
    protected function configure()
    {
        $this->setName('app:google:usersync')->setDescription('Syncs users with the G Suite.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Google Usersync');

        $googleHelper = $this->getContainer()->get('app.helper.google');
        $googleHelper->getAllUsers($this->getContainer()->getParameter('google_apps_domain'));

        $io->writeln('Done');
    }
}
