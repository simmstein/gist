<?php

namespace Gist\Form;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CreateGistForm
 * @author Simon Vieille <simon@deblan.fr>
 */
class FilterGistForm extends AbstractForm
{
    public function build(array $options = array())
    {
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
                    'anyway' => $this->translator->trans('form.cipher.choice.anyway'),
                    'no' => $this->translator->trans('form.cipher.choice.no'),
                    'yes' => $this->translator->trans('form.cipher.choice.yes'),
                ),
                'constraints' => array(
                    new NotBlank(),
                ),
            )
        );

        $this->builder->setMethod('GET');

        return $this->builder;
    }

    protected function getTypes()
    {
        $types = array(
            'all' => '',
            'html' => '',
            'css' => '',
            'javascript' => '',
            'php' => '',
            'sql' => '',
            'xml' => '',
            'yaml'=> '',
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

    public function getName()
    {
        return 'filter';
    }
}
