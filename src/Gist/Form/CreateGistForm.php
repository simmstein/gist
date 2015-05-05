<?php

namespace Gist\Form;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CreateGistForm
 * @author Simon Vieille <simon@deblan.fr>
 */
class CreateGistForm extends AbstractForm
{
    public function build(array $options = array())
    {
        $this->builder->add(
            'title',
            'text',
            array(
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('form.title.placeholder'),
                ),
            )
        );

        $this->builder->add(
            'content',
            'textarea',
            array(
                'required' => true,
                'attr' => array(
                    'class' => 'form-control',
                    'rows' => 10,
                ),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => $this->translator->trans('form.error.not_blank'),
                    )),
                ),
            )
        );

        $this->builder->add(
            'type',
            'choice',
            array(
                'required' => true,
                'choices' => $this->getTypes(),
                'constraints' => array(
                    new NotBlank(),
                ),
            )
        );

        $this->builder->add(
            'cipher',
            'choice',
            array(
                'required' => true,
                'choices' => array(
                    'no' => $this->translator->trans('form.cipher.choice.no'),
                    'yes' => $this->translator->trans('form.cipher.choice.yes'),
                ),
            )
        );

        return $this->builder->getForm();
    }

    protected function getTypes()
    {
        $types = array(
            'xml' => '',
            'css' => '',
            'js' => '',
            'php' => '',
            'sql' => '',
            'yaml'=> '',
            'perl' => '',
            'c' => '',
            'asp' => '',
            'python' => '',
            'bash' => '',
            'as' => '',
            'text' => '',
        );

        foreach ($types as $k => $v) {
            $types[$k] = $this->translator->trans('form.type.choice.'.$k);
        }

        return $types;
    }
}
