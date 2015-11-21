<?php

namespace Gist\Controller;

use Gist\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Gist\Model\User;
use Gist\Form\UserRegisterForm;

/**
 * Class LoginController
 * @author Simon Vieille <simon@deblan.fr>
 */
class LoginController extends Controller
{
    public function registerAction(Request $request, Application $app)
    {
        $user = $app['user.provider']->createUser();

        $form = new UserRegisterForm(
            $app['form.factory'],
            $app['translator'],
            $user
        );

        $form = $form->build()->getForm();

        if ($request->isMethod('post')) {
            $form->submit($request);

            if ($form->isValid()) {
                if ($app['user.provider']->userExists($user->getUsername())) {
                    $error = $app['translator']->trans('login.register.already_exists');
                } else {
                    $app['user.provider']->registerUser(
                        $user,
                        $user->getPassword()
                    );

                    $success = $app['translator']->trans('login.register.registred');
                }
            }
        }

        return $app['twig']->render(
            'Login/register.html.twig',
            [
                'form'    => $form->createView(),
                'error'   => isset($error) ? $error : '',
                'success' => isset($success) ? $success : '',
            ] 
        );
    }
}

