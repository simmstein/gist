<?php

namespace Gist\Model;

use Gist\Model\Base\User as BaseUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
.
/**
 * Class User.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class User extends BaseUser implements UserInterface
{
    /**
     * Erases credentials.
     *
     * @return void
     */
    public function eraseCredentials()
    {
    }

    /**
     * Returns roles.
     *
     * @return array
     */
    public function getRoles()
    {
        return explode(',', parent::getRoles());
    }

    /**
     * {@inheritdoc}
     */
    public function getGists(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        if ($criteria === null) {
            $criteria = GistQuery::create()->orderById(Criteria::DESC);
        }

        return parent::getGists($criteria, $con);
    }

    /**
     * Generates a pager of the user's gists.
     *
     * @param int $page
     * @param array $options
     * @param int $maxPerPage
     *
     * @return Propel\Runtime\Util\PropelModelPager
     */
    public function getGistsPager($page, $options = array(), $maxPerPage = 10) 
    {
        $query = GistQuery::create()
            ->filterByUser($this)
            ->orderByCreatedAt(Criteria::DESC);

        if (!empty($options['type']) && $options['type'] !== 'all') {
            $query->filterByType($options['type']);
        }
        
        if (!empty($options['cipher']) && $options['cipher'] !== 'anyway') {
            $bools = array(
                'yes' => true,
                'no' => false,
            );

            $query->filterByCipher($bools[$options['cipher']]);
        }

        return $query->paginate($page, $maxPerPage);
    }
}
