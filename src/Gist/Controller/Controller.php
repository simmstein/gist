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
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getApp()
    {
        return $this->app;
    }

    protected function notFoundResponse()
    {
        $app = $this->getApp();

        return new Response(
            $app['twig']->render(
                'View/notFound.html.twig',
                []
            ),
            404
        );
    }
    
    protected function getViewOptions(Request $request, $gist, $commit)
    {
        $app = $this->getApp();

        $gist = GistQuery::create()->findOneByFile($gist);

        if (null === $gist) {
            return null;
        }

        $history = $app['gist']->getHistory($gist);

        if (empty($history)) {
            return null;
        }

        $content = $this->getContentByCommit($gist, $commit, $history);

        return array(
            'gist' => $gist,
            'type' => $gist->getType(),
            'history' => $history,
            'commit' => $commit,
            'raw_content' => $content,
            'content' => $app['gist']->highlight($gist->getGeshiType(), $content),
        );
    }

    protected function getContentByCommit(Gist $gist, &$commit, $history)
    {
        $app = $this->getApp();

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

    public function getUser()
    {
        $app = $this->getApp();

        $securityContext = $app['security.token_storage'];
        $securityToken = $securityContext->getToken();

        if (!$securityToken) {
            return null;
        }

        $user = $securityToken->getUser();

        if (!is_object($user)) {
            return null;
        }

        return $user;
    }

    public function render($template, array $params = null)
    {
        $app = $this->getApp();

        if (null === $params) {
            $params = [];
        }

        if (!isset($params['user'])) {
            $params['user'] = $this->getUser();
        }

        return $app['twig']->render(
            $template,
            $params
        );
    }
}
