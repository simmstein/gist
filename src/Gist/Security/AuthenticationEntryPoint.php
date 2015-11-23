<?php

namespace Gist\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * Class AuthenticationEntryPoint
 * @author Simon Vieille <simon@deblan.fr>
 */
class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    protected $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        if ($request->isXmlHttpRequest()) {
            $response = new Response(json_encode([]), 401);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        if ($authException->getMessage() !== 'Full authentication is required to access this resource.') {
            $params = ['error' => 1];
        } else {
            $params = [];
        }

        return new RedirectResponse($this->urlGenerator->generate('_login', $params));
    }
}
