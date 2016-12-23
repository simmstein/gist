<?php

namespace Gist\Command\Migration;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Gist\Model\GistQuery;

/**
 * class UpgradeTo1p4p1Command.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class UpgradeTo1p4p1Command extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('migrate:to:v1.4.1')
            ->setDescription('Migrates database entries to >= v1.4.1')
            ->setHelp('The <info>%command.name%</info> migrates database entries to >= v1.4.1');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getSilexApplication();
        $gists = GistQuery::create()
            ->filterByCommits(0)
            ->find();

        foreach ($gists as $gist) {
            $commits = $app['gist']->getNumberOfCommits($gist);
            $gist->setCommits($commits);
            $gist->save();
        }
    }
}
