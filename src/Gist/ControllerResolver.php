<?php

namespace Gist;

use Silex\ControllerResolver as BaseControllerResolver;
use Gist\Application;

/**
 * Class DecoratorControllerResolver
 * @author Simon Vieille <simon@deblan.fr>
 */
class ControllerResolver extends BaseControllerResolver
{
    protected function instantiateController($class)
    {
        return new $class($this->app);
    }
}

