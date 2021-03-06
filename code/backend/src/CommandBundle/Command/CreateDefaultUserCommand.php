<?php

namespace CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDefaultUserCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
        ->setName('command:user:create:default')
        ->setDescription('Create some default users.')
        ->setDefinition(array(
            new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin'),
            new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
        ))
        ->setHelp(<<<EOT
Creates default users.
EOT
        );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $random = rand(0, 800);

        if ($random > 700) {
            $this->getContainer()->get('command.test')->test();
        }

        $usernames   = ["{$random}test", "{$random}admin"];
        $emails      = ["{$random}test@test.com", "{$random}admin@admin.com"];
        $password   = 'today123';
        $inactive   = $input->getOption('inactive');
        $superadmin = $input->getOption('super-admin');

        $manipulator = $this->getContainer()->get('fos_user.util.user_manipulator');

        foreach ($usernames as $index => $username) {
            $manipulator->create($username, $password, $emails[$index], !$inactive, $superadmin);
            $output->writeln(sprintf('CONSOLE: Created user <comment>%s</comment> with email %s', $username, $emails[$index]));
            error_log(sprintf('ERROR LOG: Created user %s with email %s', $username, $emails[$index]));
        }
    }
}
