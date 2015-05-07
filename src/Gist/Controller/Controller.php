<?php

namespace Gist\Controller;

use Silex\Application;
use Gist\Model\Gist;
use Symfony\Component\HttpFoundation\Request;
use Gist\Model\GistQuery;

/**
 * Class Controller
 * @author Simon Vieille <simon@deblan.fr>
 */
class Controller
{
    protected function notFoundResponse(Application $app)
    {
        return $app['twig']->render('View/notFound.html.twig');
    }

    protected function getViewOptions(Request $request, Application $app, $gist, $commit)
    {
        $gist = GistQuery::create()->findOneByFile($gist);

        if (null === $gist) {
            return null;
        }

        $history = $app['gist']->getHistory($gist);

        if (empty($history)) {
            return null;
        }

        $content = $this->getContentByCommit($app, $gist, $commit, $history);

        return array(
            'gist' => $gist,
            'type' => $gist->getType(),
            'history' => $history,
            'commit' => $commit,
            'raw_content' => $content,
            'content' => $app['gist']->highlight($gist->getType(), $content),
        );
    }

    protected function getContentByCommit(Application $app, Gist $gist, &$commit, $history)
    {
        if ($commit === 0) {
            $commit = $history[0]['commit'];
        } else {
            $commitExists = false;

            foreach ($history as $ci) {
                if ($commit === $ci['commit']) {
                    $commitExists = true;
                }
            }

            if (!$commitExists) {
                return null;
            }
        }

        return $app['gist']->getContent($gist, $commit);
    }
}
