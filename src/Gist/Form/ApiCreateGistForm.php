<?php

namespace Gist\Form;

/**
 * Class CreateGistForm
 * @author Simon Vieille <simon@deblan.fr>
 */
class ApiCreateGistForm extends CreateGistForm
{
    public function build(array $options = array())
    {
        parent::build($options);

        $this->builder->remove('cipher');

        return $this->builder;
    }
}
