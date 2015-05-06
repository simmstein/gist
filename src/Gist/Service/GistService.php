<?php

namespace Gist\Service;

use Gist\Model\Gist;
use GitWrapper\GitWorkingCopy;

/**
 * Class GistService
 * @author Simon Vieille <simon@deblan.fr>
 */
class GistService
{
    protected $gistPath;

    protected $git;

    public function __construct($gistPath, GitWorkingCopy $git)
    {
        $this->gistPath = $gistPath;
        $this->git = $git;
    }

    public function create(Gist $gist, array $data)
    {
        $gist->hydrateWith($data);
        $gist->generateFilename();
        $dir = getcwd();

        chdir($this->gistPath);
        file_put_contents($this->gistPath.'/'.$gist->getFile(), $data['content']);

        $this->git
            ->add($gist->getFile())
            ->commit('Init');

        chdir($dir);

        $gist->save();

        return $gist;
    }
}
