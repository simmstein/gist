<?php

namespace Gist\Security;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LogoutSuccessHandler.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function onLogoutSuccess(Request $request)
    {
        $targetUrl = $request->query->get('target_url') ? $request->query->get('target_url') : '/';

        return new RedirectResponse($targetUrl);
    }
}
