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
 * Class Gist.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class Gist
{
    /**
     * @var string
     */
    protected $gistPath;

    /**
     * @var GitWrapper
     */
    protected $gitWrapper;

    /**
     * @var GitWorkingCopy
     */
    protected $gitWorkingCopy;

    /**
     * @var GeSHi
     */
    protected $geshi;

    /**
     * __construct.
     *
     * @param mixed          $gistPath
     * @param GitWrapper     $gitWrapper
     * @param GitWorkingCopy $gitWorkingCopy
     * @param GeSHi          $geshi
     */
    public function __construct($gistPath, GitWrapper $gitWrapper, GitWorkingCopy $gitWorkingCopy, GeSHi $geshi)
    {
        $this->gistPath = $gistPath;
        $this->gitWrapper = $gitWrapper;
        $this->gitWorkingCopy = $gitWorkingCopy;
        $this->geshi = $geshi;
    }

    /**
     * Returns a collection of gists.
     *
     * @return Propel\Runtime\Collection\ObjectCollection
     */
    public function getGists()
    {
        return GistQuery::create()->find();
    }

    /**
     * Returns the history of a Gist.
     *
     * @param GistModel $gist
     *
     * @return array
     */
    public function getHistory(GistModel $gist)
    {
        $command = GitCommand::getInstance('log', '--format=medium', $gist->getFile());
        $command->setDirectory($this->gistPath);
        $command->bypass(false);
        $output = $this->gitWrapper->run($command);

        preg_match_all('/commit ([^\n]+)\n/isU', $output, $commits, PREG_SET_ORDER);
        preg_match_all('/Date:\s+([^\n]+)\n/isU', $output, $dates, PREG_SET_ORDER);

        $history = [];

        for ($i = count($commits) - 1; $i >= 0; --$i) {
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

            if ($gist->isCipher()) {
                $data['content'] = $this->getContent($gist, $commit);
            }

            $history[] = $data;
        }

        return $history;
    }

    /**
     * Returns the content of a gist.
     *
     * @param GistModel $gist
     * @param string    $commit
     *
     * @return string
     */
    public function getContent(GistModel $gist, $commit)
    {
        $command = GitCommand::getInstance('cat-file', '-p', $commit.':'.$gist->getFile());
        $command->setDirectory($this->gistPath);
        $command->bypass(false);

        return str_replace("\r\n", "\n", $this->gitWrapper->run($command));
    }

    /**
     * Creates a gist.
     *
     * @param GistModel $gist
     * @param array     $data
     * @param mixed     $user
     *
     * @return GistModel
     */
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

        $gist->commit()->save();

        return $gist;
    }

    /**
     * Makes a commit.
     *
     * @param GistModel $gist
     * @param array     $data
     *
     * @return GistModel
     */
    public function commit(GistModel $gist, array $data)
    {
        file_put_contents($this->gistPath.'/'.$gist->getFile(), $data['content']);

        $this->gitWorkingCopy
            ->add($gist->getFile())
            ->commit('Update');

        $gist->commit()->save();

        return $gist;
    }

    /*
     * Returns the number of commits.
     *
     * @param GistModel $gist
     *
     * @return int
     */
    public function getNumberOfCommits(GistModel $gist)
    {
        $command = GitCommand::getInstance('log', '--oneline', '--', $gist->getFile());
        $command->setDirectory($this->gistPath);
        $command->bypass(false);

        $content = trim($this->gitWrapper->run($command));
        $content = str_replace("\r\n", "\n", $content);

        return count(explode("\n", $content));
    }

    /**
     * Highlight the content.
     *
     * @param string $type
     * @param string $content
     *
     * @return string
     */
    public function highlight($type, $content)
    {
        $this->geshi->set_source($content);
        $this->geshi->set_language($type);

        return $this->geshi->parse_code();
    }
}
