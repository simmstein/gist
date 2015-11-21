<?php

namespace Gist\Model;

use Gist\Model\Base\User as BaseUser;
use Symfony\Component\Security\Core\User\UserInterface;

class User extends BaseUser implements UserInterface
{
    public function eraseCredentials()
    {
        $this->setPassword(null);
    }

    public function getRoles()
    {
        return explode(',', parent::getRoles());
    }
}
