<?php

namespace Gist\Model;

use Gist\Model\Base\User as BaseUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;

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

    public function getGists(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        if ($criteria === null) {
            $criteria = GistQuery::create()->orderById(Criteria::DESC);
        }

        return parent::getGists($criteria, $con);
    }
}
