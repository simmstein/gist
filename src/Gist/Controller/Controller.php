<?php

namespace Gist\Controller;

use Silex\Application;
use Gist\Model\Gist;
use Symfony\Component\HttpFoundation\Request;
use Gist\Model\GistQuery;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Controller
 * @author Simon Vieille <simon@deblan.fr>
 */
class Controller
{
    protected function notFoundResponse(Application $app)
    {
        return new Response(
            $app['twig']->render(
                'View/notFound.html.twig',
                []
            ),
            404
        );
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
            'content' => $app['gist']->highlight($gist->getGeshiType(), $content),
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

    public function getUser(Application $app)
    {
        $securityContext = $app['security'];
        $securityToken = $securityContext->getToken();

        if (!$securityToken) {
            return null;
        }

        return $securityToken->getUser();
    }

    public function render($template, array $params, Application $app)
    {
        if (!isset($params['user'])) {
            $params['user'] = $this->getUser($app);
        }

        return $app['twig']->render(
            $template,
            $params
        );
    }
}
