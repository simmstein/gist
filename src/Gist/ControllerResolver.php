<?php

namespace Gist;

use Silex\ControllerResolver as BaseControllerResolver;

/**
 * Class DecoratorControllerResolver.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class ControllerResolver extends BaseControllerResolver
{
    /**
     * Instanciates a controller.
     *
     * @param string $class
     *
     * @return Gist\Controller
     */
    protected function instantiateController($class)
    {
        return new $class($this->app);
    }
}
