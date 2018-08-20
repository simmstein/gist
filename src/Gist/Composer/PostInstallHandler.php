<?php

namespace Gist\Composer;

use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Composer\Script\Event;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Yaml;

/**
 * class PostInstallHandler.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class PostInstallHandler
{
    public static function execute(Event $event)
    {
        $helper = new QuestionHelper();
        $input = new ArgvInput();
        $output = new ConsoleOutput();
        $filesystem = new Filesystem();

        $output->writeln('+==============================+');
        $output->writeln('| GIST Setup                   |');
        $output->writeln('+==============================+');
        $output->writeln('');
        $output->writeln('1. Database');
        $output->writeln('===========');

        $configure = true;
        if ($filesystem->exists('propel.yaml')) {
            $output->writeln('The configuration file exists.');
            $question = new ConfirmationQuestion('Your current configuration will not be merged. Do you want to override it? [y/N] ', false);
            $configure = $helper->ask($input, $output, $question);
        } else {
            $configure = true;
        }

        if ($configure) {
            $choices = ['MySQL/MariaDB', 'SQLite', 'other'];
            $question = new ChoiceQuestion('Which DBMS? ', $choices, 0);
            $dbms = $helper->ask($input, $output, $question);
            $substitutions = [];

            if ($dbms === 'MySQL/MariaDB') {
                $templateName = 'propel.yaml.dist-mysql';

                $question = new Question('Host: [127.0.0.1] ', '127.0.0.1');
                $substitutions['DATABASE_HOST'] = $helper->ask($input, $output, $question);

                $question = new Question('Database (it must exists!): [gist] ', 'gist');
                $substitutions['DATABASE_NAME'] = $helper->ask($input, $output, $question);

                $question = new Question('Username: [root] ', 'root');
                $substitutions['DATABASE_USERNAME'] = $helper->ask($input, $output, $question);

                $question = new Question('Password: [] ', '');
                $substitutions['DATABASE_PASSWORD'] = $helper->ask($input, $output, $question);
            } elseif ($dbms === 'SQLite') {
                $defaultPath = getcwd().'/data/gist.sqlite';
                $question = new Question("Ok! Where do you want me to save datas? [$defaultPath] ", $defaultPath);
                $substitutions['DATABASE_PATH'] = $helper->ask($input, $output, $question);
                $templateName = 'propel.yaml.dist-sqlite';
            } else {
                $output->writeln('See README.md to perform the configuration.');

                return;
            }

            $template = file_get_contents('app/config/'.$templateName);
            $content = str_replace(
                array_keys($substitutions),
                array_values($substitutions),
                $template
            );

            $done = file_put_contents('propel.yaml', $content) !== false;

            if ($done) {
                $output->writeln('Running migration...');

                foreach (['config:convert', 'model:build --recursive', 'migration:diff --recursive', 'migration:migrate --recursive'] as $arg) {
                    $command = self::getPhp(true).' ./vendor/propel/propel/bin/propel '.$arg;
                    $process = new Process($command);
                    $process->run();

                    if (!$process->isSuccessful()) {
                        $output->writeln('An error occured while executing:');
                        $output->writeln($command);
                        $output->writeln('To perform the configuration. See README.md.');

                        return;
                    }
                }

                $output->writeln('Done!');
            } else {
                $output->writeln('An error occured. See README.md to perform the configuration.');
            }
        }

        $output->writeln('');
        $output->writeln('2. Application');
        $output->writeln('==============');

        $configure = true;
        if ($filesystem->exists('app/config/config.yml')) {
            $output->writeln('The configuration file exists.');
            $question = new ConfirmationQuestion('Your current configuration will not be merged. Do you want to override it? [y/N] ', false);
            $configure = $helper->ask($input, $output, $question);
        } else {
            $configure = true;
        }

        if ($configure) {
            $output->writeln('');
            $output->writeln(' 2.1 Security');
            $output->writeln('-------------');

            $token = str_shuffle(sha1(microtime().uniqid()));

            $question = new ConfirmationQuestion('Registration enabled: [Y/n] ', true);
            $enableRegistration = $helper->ask($input, $output, $question);

            $question = new ConfirmationQuestion('Login enabled: [Y/n] ', true);
            $enableLogin = $helper->ask($input, $output, $question);

            $question = new ConfirmationQuestion('Login required to edit a gist: [y/N] ', false);
            $loginRequiredToEditGist = $helper->ask($input, $output, $question);

            $question = new ConfirmationQuestion('Login required to view a gist: [y/N] ', false);
            $loginRequiredToViewGist = $helper->ask($input, $output, $question);

            $question = new ConfirmationQuestion('Login required to view an embeded gist: [y/N] ', false);
            $loginRequiredToViewEmbededGist = $helper->ask($input, $output, $question);

            $output->writeln('');
            $output->writeln(' 2.2 API');
            $output->writeln('--------');

            $question = new ConfirmationQuestion('API enabled: [Y/n] ', true);
            $apiEnabled = $helper->ask($input, $output, $question);

            if ($apiEnabled) {
                $question = new ConfirmationQuestion('API key required: [y/N] ', false);
                $apikeyRequired = $helper->ask($input, $output, $question);
            } else {
                $apikeyRequired = false;
            }

            $question = new Question('[Client] API base URL: [https://gist.deblan.org/] ', 'https://gist.deblan.org/');
            $apiBaseUrl = $helper->ask($input, $output, $question);

            $question = new Question('[Client] API key: [] ', '');
            $apiClientApiKey = $helper->ask($input, $output, $question);

            $configuration = [
                'security' => [
                    'token' => $token,
                    'enable_registration' => $enableRegistration,
                    'enable_login' => $enableLogin,
                    'login_required_to_edit_gist' => $loginRequiredToEditGist,
                    'login_required_to_view_gist' => $loginRequiredToViewGist,
                    'login_required_to_view_embeded_gist' => $loginRequiredToViewEmbededGist,
                ],
                'api' => [
                    'enabled' => $apiEnabled,
                    'api_key_required' => $apikeyRequired,
                    'base_url' => $apiBaseUrl,
                    'client' => [
                        'api_key' => $apiClientApiKey,
                    ],
                ],
                'data' => [
                    'path' => 'data/git',
                ],
                'git' => [
                    'path' => '/usr/bin/git',
                ],
                'theme' => [
                    'name' => 'dark',
                ],
            ];

            $content = (new Yaml())->dump($configuration);

            $done = file_put_contents('app/config/config.yml', $content);
        }

        $output->writeln('');
        $output->writeln('Configuration finished!');
    }

    protected static function getPhp($includeArgs = true)
    {
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find($includeArgs)) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }
}
