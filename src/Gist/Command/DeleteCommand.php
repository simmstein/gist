<?php

namespace Gist\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * class DeleteCommand.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class DeleteCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('delete')
            ->setDescription('Delete a gist using the API')
            ->addOption('gist', null, InputOption::VALUE_REQUIRED, 'Id or File of the gist')
            ->setHelp(<<<'EOF'
Provides a client to delete a gist using the API.

Arguments:
    none.

Options:
    <info>--gist</info>
        Defines the Gist to delete by using its Id or its File
EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->getSilexApplication()['api_client']->delete($input->getOption('gist'));

        $output->writeln(empty($result['error']) ? 'OK' : '<error>An error occured.</error>');
    }
}
