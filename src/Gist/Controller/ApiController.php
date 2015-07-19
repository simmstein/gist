<?php

namespace Gist\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Gist\Model\Gist;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gist\Form\ApiCreateGistForm;

/**
 * Class ApiController
 * @author Simon Vieille <simon@deblan.fr>
 */
class ApiController extends Controller
{
    public function createAction(Request $request, Application $app)
    {
        if (false === $request->isMethod('post')) {
            return $this->invalidMethodResponse('POST method is required.');
        }

        $form = new ApiCreateGistForm(
            $app['form.factory'],
            $app['translator'],
            [],
            ['csrf_protection' => false]
        );

        $form = $form->build()->getForm();

        $form->submit($request);

        if ($form->isValid()) {
            $gist = $app['gist']->create(new Gist(), $form->getData());
            $gist->setCipher(false)->save();

            $history = $app['gist']->getHistory($gist);

            return new JsonResponse(array(
                'url' => $request->getSchemeAndHttpHost().$app['url_generator']->generate(
                    'view',
                    array(
                        'gist' => $gist->getFile(),
                        'commit' => array_pop($history)['commit'],
                    )
                ),
                'gist' => $gist->toArray(),
            ));
        }

        return $this->invalidRequestResponse('Invalid field(s)');
    }

    protected function invalidMethodResponse($message = null)
    {
        $data = [
            'error' => 'Method Not Allowed',
            'message' => $message,
        ];

        return new JsonResponse($data, 405);
    }

    protected function invalidRequestResponse($message = null)
    {
        $data = [
            'error' => 'Bad request',
            'message' => $message,
        ];

        return new JsonResponse($data, 400);
    }
}
