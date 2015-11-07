<?php

namespace Gist\Form;

/**
 * Class ApiUpdateGistForm
 * @author Simon Vieille <simon@deblan.fr>
 */
class ApiUpdateGistForm extends ApiCreateGistForm
{
    public function build(array $options = array())
    {
        parent::build($options);

        $this->builder
            ->remove('title')
            ->remove('type');

        return $this->builder;
    }
}
