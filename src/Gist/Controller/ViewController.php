<?php

namespace Gist\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Gist\Model\GistQuery;
use Gist\Model\Gist;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ViewController
 * @author Simon Vieille <simon@deblan.fr>
 */
class ViewController extends Controller
{
    public function viewAction(Request $request, $gist, $commit)
    {
        $app = $this->getApp();

        $viewOptions = $this->getViewOptions($request, $gist, $commit);

        if (is_array($viewOptions)) {
            return $this->render('View/view.html.twig', $viewOptions);
        } else {
            return $this->notFoundResponse();
        }
    }

    public function embedAction(Request $request, $gist, $commit)
    {
        $app = $this->getApp();

        $viewOptions = $this->getViewOptions($request, $gist, $commit);

        if (is_array($viewOptions)) {
            return $app['twig']->render('View/embed.html.twig', $viewOptions);
        } else {
            return $this->notFoundResponse();
        }
    }

    public function embedJsAction(Request $request, $gist, $commit)
    {
        $viewOptions = $this->getViewOptions($request, $gist, $commit);

        return new Response(
            $this->render('View/embedJs.html.twig', $viewOptions),
            200,
            array(
                'Content-Type' => 'text/javascript',
            )
        );
    }

    public function rawAction(Request $request, $gist, $commit)
    {
        $viewOptions = $this->getViewOptions($request, $gist, $commit);

        if (is_array($viewOptions)) {
            return new Response(
                $viewOptions['raw_content'],
                200,
                array(
                    'Content-Type' => 'text/plain',
                )
            );
        } else {
            return $this->notFoundResponse();
        }
    }

    public function downloadAction(Request $request, $gist, $commit)
    {
        $app = $this->getApp();

        $viewOptions = $this->getViewOptions($request, $gist, $commit);

        if (is_array($viewOptions)) {
            $gist = $viewOptions['gist'];
            $file = $app['gist_path'].'/'.$gist->getFile();

            return new Response(
                $viewOptions['raw_content'],
                200,
                array(
                    'Content-Disposition' => sprintf('filename=%s.%s', $gist->getFile(), $gist->getTypeAsExtension()),
                    'Content-Length' => filesize($file),
                    'Content-Type' => 'application/force-download',
                )
            );
        } else {
            return $this->notFoundResponse($app);
        }
    }

    public function revisionsAction(Request $request, $gist)
    {
        $app = $this->getApp();

        $gist = GistQuery::create()->findOneByFile($gist);

        if (null === $gist) {
            return $this->notFoundResponse();
        }

        $history = $app['gist']->getHistory($gist);

        if (empty($history)) {
            return $this->notFoundResponse();
        }

        return $this->render(
            'View/revisions.html.twig',
            array(
                'gist' => $gist,
                'history' => $history,
            )
        );
    }
}
