<?php

namespace Gist\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\Table;
use GitWrapper\GitException;

/**
 * class StatsCommand;
 * 
 * @author Simon Vieille <simon@deblan.fr> 
 */
class StatsCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('stats')
            ->setDescription('Show stats about GIST')
            ->setHelp(<<<EOF
Show stats about GIST
EOF
            );
    }
    
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $gistService = $this->getSilexApplication()['gist'];
        $gists = $gistService->getGists();

        $languages  = [];
        $withEncryption = [];
        $commits = [];
        $total = 0;

        foreach ($gists as $gist) {
            $total++;

            if (!array_key_exists($gist->getType(), $languages)) {
                $languages[$gist->getType()] = 0;
                $withEncryption[$gist->getType()] = 0;
                $commits[$gist->getType()] = 0;
            }
            
            if ($gist->getCipher()) {
                $withEncryption[$gist->getType()]++;
            }
                
            $languages[$gist->getType()]++;
            try {
                $count = count($gistService->getHistory($gist));
                $commits[$gist->getType()] += $count;
            } catch(GitException $e) {
            
            }
        }

        $output->writeln(['<comment>Gists statistics</comment>', '']);

        $table = new Table($output);
        $table
            ->setHeaders(array('Without encryption', 'With encryption', 'Commits', 'Total'))
            ->setRows(array(
                array(
                    $total - $v = array_sum($withEncryption), 
                    $v, 
                    array_sum($commits), 
                    $total
                ),
            ))
        ;
        $table->render();

        ksort($languages);
        ksort($withEncryption);
        ksort($commits);

        $output->writeln(['', '<comment>Details by type</comment>', '']);

        $table->setHeaders(array(
            'Type',
            'Without encryption', 
            'With encryption', 
            'Commits', 
            'Total',
        ));

        $rows = [];

        foreach ($languages as $lang => $total) {
            $totalWithoutEncyption = $total - $withEncryption[$lang];
            $totalWithEncryption = $total - $totalWithoutEncyption;

            $rows[] = array(
                $lang,
                $totalWithoutEncyption,
                $totalWithEncryption,
                $commits[$lang],
                $total,
            );
        }

        $table->setRows($rows);
        $table->render();
    }
}
