<?php

namespace Gist\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Gist\Service\UserProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class AuthenticationProvider.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class AuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var UserProvider
     */
    protected $userProvider;

    /**
     * __construct.
     *
     * @param UserProvider $userProvider
     */
    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * Authenticates.
     *
     * @param TokenInterface $token
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUser());

        if ($user) {
            $isValid = $this->userProvider->getEncoder()->isPasswordValid(
                $user->getPassword(),
                $token->getCredentials(),
                $user->getSalt()
            );

            if (!$isValid) {
                throw new AuthenticationException('Authentication failed.');
            }

            return;
        }

        throw new AuthenticationException('Authentication failed.');
    }

    /**
     * Returns if the token instance is supported.
     *
     * @param TokenInterface $token
     *
     * @return bool
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof UsernamePasswordToken;
    }
}
