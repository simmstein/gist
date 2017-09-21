<?php

namespace Gist\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Propel\Runtime\Parser\YamlParser;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Helper\Table;
use DateTime;

/**
 * class ListCommand.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class ListCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('gists')
            ->setDescription('List gists using the API');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $gists = $this->getSilexApplication()['api_client']->list();
        $rows = [];

        foreach ($gists as $gist) {
            $rows[] = array(
                $gist['id'],
                $gist['title'],
                $gist['cipher'] ? 'y' : 'n',
                $gist['type'],
                (new DateTime($gist['createdAt']))->format('Y-m-d H:i:s'),
                (new DateTime($gist['updatedAt']))->format('Y-m-d H:i:s'),
                $gist['url'],
            );
        }

        $table = new Table($output);
        $table
            ->setHeaders(array('ID', 'Title', 'Cipher', 'Type', 'Created At', 'Updated At', 'url'))
            ->setRows($rows);

        $table->render();
    }
}
