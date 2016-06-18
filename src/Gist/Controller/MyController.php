<?php

namespace Gist\Controller;

use Symfony\Component\HttpFoundation\Request;
use Gist\Model\GistQuery;
use Gist\Form\DeleteGistForm;

/**
 * Class MyController
 * @author Simon Vieille <simon@deblan.fr>
 */
class MyController extends Controller
{
    public function myAction(Request $request, $page)
    {
        $page = (int) $page;
        $gists = $this->getUser()->getGistsPager($page);
        
        $app = $this->getApp();
        $form = new DeleteGistForm($app['form.factory'], $app['translator']);
        $form = $form->build()->getForm();

        if ($request->isMethod('post')) {
            $form->submit($request);

            if ($form->isValid()) {
                $id = (int) $form->getData()['id'];

                foreach ($gists as $gist) {
                    if ($gist->getId() === $id) {
                        $gist->delete();
                        $deleted = true;
                        $gists = $this->getUser()->getGistsPager($page);
                    }
                }
            }
        }

        $nextPage = min($page + 1, $gists->getLastPage());
        $previousPage = max($page - 1, 1);
        
        return $this->render(
            'My/my.html.twig',
            array(
                'gists'        => $gists,
                'page'         => $page,
                'form'         => $form->createView(),
                'deleted'      => !empty($deleted),
                'nextPage'     => $nextPage,
                'previousPage' => $previousPage,
            )
        );
    }
}
