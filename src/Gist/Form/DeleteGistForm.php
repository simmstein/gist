<?php

namespace Gist\Form;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class DeleteGistForm
 * @author Simon Vieille <simon@deblan.fr>
 */
class DeleteGistForm extends AbstractForm
{
    public function build(array $options = array())
    {
        $this->builder->add(
            'id',
            'hidden',
            array(
                'required' => true,
                'constraints' => array(
                    new NotBlank(),
                ),
            )
        );
        
        $this->builder->setMethod('POST');
        
        return $this->builder;
    }

    public function getName()
    {
        return 'delete';
    }
}
