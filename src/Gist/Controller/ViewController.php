<?php

namespace Gist\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Gist\Model\GistQuery;
use Gist\Model\Gist;

/**
 * Class HomeController
 * @author Simon Vieille <simon@deblan.fr>
 */
class ViewController
{
    public function viewAction(Request $request, Application $app, $gist, $commit)
    {
        $gist = GistQuery::create()->findOneByFile($gist);

        if (null === $gist) {
            return $this->notFoundResponse($app);
        }

        $history = $app['gist']->getHistory($gist);

        if (empty($history)) {
            return $this->notFoundResponse($app);
        }

        $content = $this->getContentByCommit($app, $gist, $commit, $history);

        return $app['twig']->render(
            'View/view.html.twig',
            array(
                'gist' => $gist,
                'type' => $gist->getType(),
                'history' => $history,
                'content' => $app['gist']->highlight($gist->getType(), $content),
            )
        );
    }

    protected function notFoundResponse(Application $app)
    {
        return $app['twig']->render('View/notFound.html.twig');
    }

    protected function getContentByCommit(Application $app, Gist $gist, $commit, $history)
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
