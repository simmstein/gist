<?php

namespace Gist\Command;

use Knp\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Yaml\Yaml;

/**
 * class UpdateCommand.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class UpdateCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $types = implode(', ', $this->getTypes());
        $this
            ->setName('update')
            ->setDescription('Update a gist using the API')
            ->addArgument('input', InputArgument::REQUIRED, 'Input')
            ->addOption('gist', null, InputOption::VALUE_REQUIRED, 'Id or File of the gist')
            ->addOption('all', 'a', InputOption::VALUE_NONE, 'Display all the response')
            ->addOption('id', 'i', InputOption::VALUE_NONE, 'Display only the id of the gist')
            ->addOption('json', 'j', InputOption::VALUE_NONE, 'Format the response to json')
            ->setHelp(<<<EOF
Provides a client to create a gist using the API.

Arguments:
    <info>input</info>
        Identify the source of the content: path of the file (eg: <comment>/path/to/file</comment>) or standard input (<comment>-</comment>)

    <info>type</info>
        Defines the type of code: {$types}
        Default value: <comment>text</comment>

Options:
    <info>--gist</info>
        Defines the Gist to update by using its Id or its File

    <info>--id</info>, <info>-i</info>
        Display only the id of the gist

    <info>--all</info>, <info>-a</info>
        Display all the response

    <info>--json</info>, <info>-j</info>
        Format the response to json
EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('input');
        $gist = $input->getOption('gist');
        $json = $input->getOption('json');

        if ($file === '-') {
            $content = file_get_contents('php://stdin');
        } else {
            if (!is_readable($file)) {
                $output->writeln(sprintf('<error>%s: No such file.</error>', $file));

                return false;
            }

            if (!is_file($file)) {
                $output->writeln(sprintf('<error>"%s" must be a file.</error>', $file));

                return false;
            }

            $content = file_get_contents($file);
        }

        if (trim($content) === '') {
            $output->writeln(sprintf('<error>You can not create an empty gist.</error>', $type));
        }

        $gist = $this->getSilexApplication()['api_client']->update($gist, $content);

        if ($input->getOption('id')) {
            $id = isset($gist['gist']['id']) ? $gist['gist']['id'] : $gist['gist']['Id'];
            $result = $json ? json_encode(array('id' => $id)) : $id;
        } elseif ($input->getOption('all')) {
            $result = $json ? json_encode($gist) : Yaml::dump($gist);
        } else {
            $url = $gist['url'];
            $result = $json ? json_encode(array('url' => $url)) : $url;
        }

        $output->writeln($result);
    }

    /**
     * Returns the list of types.
     *
     * @return array
     */
    protected function getTypes()
    {
        $types = array(
            'html',
            'css',
            'javascript',
            'php',
            'sql',
            'xml',
            'yaml',
            'perl',
            'c',
            'asp',
            'python',
            'bash',
            'actionscript3',
            'text',
        );

        return $types;
    }
}
