<?php

namespace Gist\Service;

use Gist\Model\UserQuery;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Gist\Model\User;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Gist\Service\SaltGenerator;

/**
 * Class UserProvider
 * @author Simon Vieille <simon@deblan.fr>
 */
class UserProvider implements UserProviderInterface
{
    protected $encoder;
    
    protected $saltGenerator;

    public function __construct(MessageDigestPasswordEncoder $encoder, SaltGenerator $saltGenerator)
    {
        $this->encoder = $encoder;
        $this->saltGenerator = $saltGenerator;
    }

    public function setEncoder(MessageDigestPasswordEncoder $encoder)
    {
        $this->encoder = $encoder;

        return $this;
    }

    public function getEncoder()
    {
        return $this->encoder;
    }

    public function setSaltGenerator(SaltGenerator $saltGenerator)
    {
        $this->saltGenerator = $saltGenerator;

        return $this;
    }

    public function getSaltGenerator()
    {
        return $this->saltGenerator;
    }

    public function userExists($username)
    {
        return UserQuery::create()
            ->filterByUsername($username)
            ->count() > 0;
    }

    public function registerUser($username, $password)
    {
        $user = new User();

        $salt = $this->saltGenerator->generate(64);

        $user
            ->setUsername($username)
            ->setRoles('ROLE_USER')
            ->setSalt($salt);

        $user
            ->setPassword($this->encoder->encodePassword($user, $password))
            ->save();

        return $user;
    }

    public function updateUserPassword(User $user, $password)
    {
        $user
            ->setPassword($this->encoder->encodePassword($password))
            ->save();

        return $user;
    }

    public function loadUserByUsername($username)
    {
        $user = UserQuery::create()->findOneByUsername($username);

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Gist\\Model\\User';
    }
}
