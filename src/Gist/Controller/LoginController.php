<?php

namespace Gist\Controller;

use Symfony\Component\HttpFoundation\Request;
use Gist\Model\User;
use Gist\Form\UserRegisterForm;
use Gist\Form\UserLoginForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class LoginController
 * @author Simon Vieille <simon@deblan.fr>
 */
class LoginController extends Controller
{
    public function registerAction(Request $request)
    {
        $app = $this->getApp();

        if (false === $app['enable_registration']) {
            return new Response('', 403);
        }

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

        return $this->render(
            'Login/register.html.twig',
            [
                'form'    => $form->createView(),
                'error'   => isset($error) ? $error : '',
                'success' => isset($success) ? $success : '',
            ]
        );
    }

    public function loginAction(Request $request)
    {
        $app = $this->getApp();

        if (false === $app['enable_login']) {
            return new Response('', 403);
        }

        $user = $app['user.provider']->createUser();

        $form = new UserLoginForm(
            $app['form.factory'],
            $app['translator'],
            $user,
            ['csrf_protection' => false]
        );

        $form = $form->build()->getForm();

        if ($request->query->get('error')) {
            $error = $app['translator']->trans('login.login.invalid');
        }

        return $this->render(
            'Login/login.html.twig',
            [
                'form'  => $form->createView(),
                'error' => isset($error) ? $error : '',
            ]
        );
    }

    public function loginCheckAction()
    {
    }

    public function logoutAction()
    {
    }
}
