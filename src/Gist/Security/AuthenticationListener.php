<?php

namespace Gist\Security;

use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Class AuthenticationListener
 * @author Simon Vieille <simon@deblan.fr>
 */
class AuthenticationListener implements ListenerInterface
{
    protected $tokenStorage;

    protected $authenticationManager;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event)
    {
        $request  = $event->getRequest();
        $username = $request->get('_username');
        $password = $request->get('_password');

        if (!empty($username)) {
            $token = new UsernamePasswordToken($username, $password, 'default');
            
            try {
                $authToken = $this->authenticationManager->authenticate($token);
                $this->tokenStorage->setToken($token);

                return;
            } catch (AuthenticationException $failed) {
                $this->tokenStorage->setToken(null);

                return;
            } 
        }
    }
}
