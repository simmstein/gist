<?php

namespace Gist\Form;

/**
 * Class ApiCreateGistForm.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class ApiCreateGistForm extends CreateGistForm
{
    /**
     * {@inheritdoc}
     */
    public function build(array $options = array())
    {
        parent::build($options);

        $this->builder->remove('cipher');

        return $this->builder;
    }
}
