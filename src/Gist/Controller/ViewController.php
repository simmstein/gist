<?php

namespace Gist\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Gist\Model\GistQuery;
use Gist\Model\Gist;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ViewController.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class ViewController extends Controller
{
    /**
     * View action.
     *
     * @param Request $request
     * @param string  $gist    Gist's ID
     * @param string  $commit  The commit
     *
     * @return string|Response
     */
    public function viewAction(Request $request, $gist, $commit)
    {
        $app = $this->getApp();

        $viewOptions = $this->getViewOptions($request, $gist, $commit);

        if (is_array($viewOptions)) {
            return $this->createResponse('View/view.html.twig', $viewOptions);
        } else {
            return $this->notFoundResponse();
        }
    }

    /**
     * Embed action.
     *
     * @param Request $request
     * @param string  $gist    Gist's ID
     * @param string  $commit  The commit
     *
     * @return string|Response
     */
    public function embedAction(Request $request, $gist, $commit)
    {
        $app = $this->getApp();

        $viewOptions = $this->getViewOptions($request, $gist, $commit);

        if (is_array($viewOptions)) {
            return $app['twig']->createResponse('View/embed.html.twig', $viewOptions);
        } else {
            return $this->notFoundResponse();
        }
    }

    /**
     * JS embed action.
     *
     * @param Request $request
     * @param string  $gist    Gist's ID
     * @param string  $commit  The commit
     *
     * @return Response
     */
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

    /**
     * Raw action.
     *
     * @param Request $request
     * @param string  $gist    Gist's ID
     * @param string  $commit  The commit
     *
     * @return Response
     */
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

    /**
     * Download action.
     *
     * @param Request $request
     * @param string  $gist    Gist's ID
     * @param string  $commit  The commit
     *
     * @return Response
     */
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
                    'Content-Length' => mb_strlen($viewOptions['raw_content']),
                    'Content-Type' => 'application/force-download',
                ),
                false
            );
        } else {
            return $this->notFoundResponse($app);
        }
    }

    /**
     * Revisions action.
     *
     * @param Request $request
     * @param string  $gist    Gist's ID
     *
     * @return Response
     */
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

        return $this->createResponse(
            'View/revisions.html.twig',
            array(
                'gist' => $gist,
                'history' => $history,
            )
        );
    }
}
