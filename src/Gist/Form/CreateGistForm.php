<?php

namespace Gist\Form;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CreateGistForm.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class CreateGistForm extends AbstractForm
{
    /**
     * {@inheritdoc}
     */
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
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'rows' => 10,
                ),
                'trim' => false,
                'constraints' => array(
                ),
            )
        );

        $this->builder->add(
            'file',
            'file',
            array(
                'required' => false,
                'attr' => array(
                ),
                'constraints' => array(
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

        return $this->builder;
    }

    /**
     * Returns the types for generating the form.
     *
     * @return array
     */
    protected function getTypes()
    {
        $types = array(
            'html' => '',
            'css' => '',
            'javascript' => '',
            'php' => '',
            'sql' => '',
            'xml' => '',
            'yaml' => '',
            'markdown' => '',
            'perl' => '',
            'c' => '',
            'asp' => '',
            'python' => '',
            'bash' => '',
            'actionscript3' => '',
            'text' => '',
        );

        foreach ($types as $k => $v) {
            $types[$k] = $this->translator->trans('form.type.choice.'.$k);
        }

        return $types;
    }
}
