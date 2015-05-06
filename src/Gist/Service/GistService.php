<?php

namespace Gist\Service;

use Gist\Model\Gist;
use GitWrapper\GitWorkingCopy;
use GitWrapper\GitWrapper;
use GitWrapper\GitCommand;
use GeSHi;

/**
 * Class GistService
 * @author Simon Vieille <simon@deblan.fr>
 */
class GistService
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

    public function getHistory(Gist $gist)
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

            $command = GitCommand::getInstance('show', $commit);
            $command->setDirectory($this->gistPath);
            $command->bypass(false);

            $diff = explode("\n", $this->gitWrapper->run($command));
            $diff = implode("\n", array_slice($diff, 11));

            $data = array(
                'commit' => trim($commits[$i][1]),
                'date' => new \DateTime(trim($dates[$i][1])),
                'diff' => $diff,
            );

            $history[] = $data;
        }

        return $history;
    }

    public function getContent(Gist $gist, $commit)
    {
        $command = GitCommand::getInstance('cat-file', '--textconv', $commit.':'.$gist->getFile());
        $command->setDirectory($this->gistPath);
        $command->bypass(false);

        return $this->gitWrapper->run($command);
    }

    public function create(Gist $gist, array $data)
    {
        $gist->hydrateWith($data);
        $gist->generateFilename();

        file_put_contents($this->gistPath.'/'.$gist->getFile(), $data['content']);

        $this->gitWorkingCopy
            ->add($gist->getFile())
            ->commit('Init');

        $gist->save();

        return $gist;
    }

    public function highlight($type, $content)
    {
        $this->geshi->set_source($content);
        $this->geshi->set_language($type);

        return $this->geshi->parse_code();
    }
}
