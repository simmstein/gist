<?php

namespace Gist\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Knp\Command\Command;
use Symfony\Component\Console\Question\Question;

class UserCreateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('user:create')
            ->setDescription('Create a user')
            ->setHelp("");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $userProvider = $this->getSilexApplication()['user.provider'];

        $username = '';
        $password = '';

        while (trim($username) === '') {
            $question = new Question('Username: ', '');
            $username = $helper->ask($input, $output, $question);
        
            if ($userProvider->userExists($username)) {
                $output->writeln('<error>This username is already used.</error>');
                $username = '';
            }
        }

        while (trim($password) === '') {
            $question = new Question('Password: ', '');
            $password = $helper->ask($input, $output, $question);
        }

        $userProvider->registerUser($username, $password);
    }
}
