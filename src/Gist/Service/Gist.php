<?php

namespace Gist\Service;

use Gist\Model\Gist as GistModel;
use GitWrapper\GitWorkingCopy;
use GitWrapper\GitWrapper;
use GitWrapper\GitCommand;
use GeSHi;
use Gist\Model\GistQuery;
use Gist\Model\User;

/**
 * Class Gist
 * @author Simon Vieille <simon@deblan.fr>
 */
class Gist
{
    protected $gistPath;

    protected $gitWrapper;

    protected $gitWorkingCopy;

    protected $geshi;

    public function __construct($gistPath, GitWrapper $gitWrapper, GitWorkingCopy $gitWorkingCopy, GeSHi $geshi)
    {
        $this->gistPath = $gistPath;
        $this->gitWrapper = $gitWrapper;
        $this->gitWorkingCopy = $gitWorkingCopy;
        $this->geshi = $geshi;
    }

    public function getGists()
    {
        return GistQuery::create()->find();
    }

    public function getHistory(GistModel $gist)
    {
        $command = GitCommand::getInstance('log', '--format=medium', $gist->getFile());
        $command->setDirectory($this->gistPath);
        $command->bypass(false);
        $output = $this->gitWrapper->run($command);

        preg_match_all('/commit ([^\n]+)\n/isU', $output, $commits, PREG_SET_ORDER);
        preg_match_all('/Date:\s+([^\n]+)\n/isU', $output, $dates, PREG_SET_ORDER);

        $history = [];

        for ($i = count($commits) - 1; $i >= 0; $i--) {
            $commit = trim($commits[$i][1]);

            $command = GitCommand::getInstance('show', '--no-color', $commit);
            $command->setDirectory($this->gistPath);
            $command->bypass(false);

            $diff = explode("\n", $this->gitWrapper->run($command));
            $diff = implode("\n", array_slice($diff, 11));

            $data = array(
                'commit' => trim($commits[$i][1]),
                'date' => new \DateTime(trim($dates[$i][1])),
                'diff' => $this->highlight('diff', $diff),
            );

            $history[] = $data;
        }

        return $history;
    }

    public function getContent(GistModel $gist, $commit)
    {
        $command = GitCommand::getInstance('cat-file', '-p', $commit.':'.$gist->getFile());
        $command->setDirectory($this->gistPath);
        $command->bypass(false);

        return str_replace("\r\n", "\n", $this->gitWrapper->run($command));
    }

    public function create(GistModel $gist, array $data, $user = null)
    {
        $gist->hydrateWith($data);
        $gist->generateFilename();

        file_put_contents($this->gistPath.'/'.$gist->getFile(), $data['content']);

        $this->gitWorkingCopy
            ->add($gist->getFile())
            ->commit('Init');

        if (is_object($user) && $user instanceof User) {
            $gist->setUser($user);
        }

        $gist->save();

        return $gist;
    }

    public function commit(GistModel $gist, array $data)
    {
        file_put_contents($this->gistPath.'/'.$gist->getFile(), $data['content']);

        $this->gitWorkingCopy
            ->add($gist->getFile())
            ->commit('Update');

        return $gist;
    }

    public function highlight($type, $content)
    {
        $this->geshi->set_source($content);
        $this->geshi->set_language($type);

        return $this->geshi->parse_code();
    }
}
