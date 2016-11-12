<?php

namespace Gist\Service;

use Gist\Model\UserQuery;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Gist\Model\User;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * Class UserProvider.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var MessageDigestPasswordEncoder
     */
    protected $encoder;

    /**
     * @var SaltGenerator
     */
    protected $saltGenerator;

    /**
     * __construct.
     *
     * @param MessageDigestPasswordEncoder $encoder
     * @param SaltGenerator                $saltGenerator
     */
    public function __construct(MessageDigestPasswordEncoder $encoder, SaltGenerator $saltGenerator)
    {
        $this->encoder = $encoder;
        $this->saltGenerator = $saltGenerator;
    }

    /**
     * Setter of encoder.
     *
     * @param MessageDigestPasswordEncoder $encoder
     *
     * @return UserProvider
     */
    public function setEncoder(MessageDigestPasswordEncoder $encoder)
    {
        $this->encoder = $encoder;

        return $this;
    }

    /**
     * Getter of encoder.
     *
     * @return MessageDigestPasswordEncoder
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * Setter of saltGenerator.
     *
     * @param SaltGenerator $saltGenerator
     *
     * @return UserProvider
     */
    public function setSaltGenerator(SaltGenerator $saltGenerator)
    {
        $this->saltGenerator = $saltGenerator;

        return $this;
    }

    /**
     * Getter of saltGenerator.
     *
     * @return SaltGenerator
     */
    public function getSaltGenerator()
    {
        return $this->saltGenerator;
    }

    /**
     * Checks if the given username is a user.
     *
     * @param string $username
     *
     * @return bool
     */
    public function userExists($username)
    {
        return UserQuery::create()
            ->filterByUsername($username)
            ->count() > 0;
    }

    /**
     * Creates a User.
     *
     * @return User
     */
    public function createUser()
    {
        return new User();
    }

    /**
     * Registers an user.
     *
     * @param User   $user
     * @param string $password
     *
     * @return User
     */
    public function registerUser(User $user, $password)
    {
        $user->setSalt($this->saltGenerator->generate());

        $user
            ->setRoles('ROLE_USER')
            ->setPassword($this->encoder->encodePassword($password, $user->getSalt()))
            ->save();

        return $user;
    }

    /**
     * Updates an user.
     *
     * @param User   $user
     * @param string $password
     *
     * @return User
     */
    public function updateUserPassword(User $user, $password)
    {
        $user
            ->setPassword($this->encoder->encodePassword($password, $user->getSalt()))
            ->save();

        return $user;
    }

    /**
     * Loads a user by his username.
     *
     * @param string $username
     *
     * @return User
     */
    public function loadUserByUsername($username)
    {
        $user = UserQuery::create()->findOneByUsername($username);

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $user;
    }

    /**
     * Refresh an user.
     *
     * @param User $user
     *
     * @return User
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Checks if the class is supported.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'Gist\Model\User';
    }
}
