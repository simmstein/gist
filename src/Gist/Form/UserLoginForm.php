<?php

namespace Gist\Form;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserLoginForm
 * @author Simon Vieille <simon@deblan.fr>
 */
class UserLoginForm extends AbstractForm
{
    public function build(array $options = array())
    {
        $this->builder->add(
            '_username',
            'text',
            array(
                'required' => true,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('login.register.form.username.placeholder'),
                ),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => $this->translator->trans('form.error.not_blank'),
                    )),
                ),
            )
        );

        $this->builder->add(
            '_password',
            'password',
            array(
                'required' => true,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('login.register.form.password.placeholder'),
                ),
                'trim' => false,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => $this->translator->trans('form.error.not_blank'),
                    )),
                ),
            )
        );

        return $this->builder;
    }

    public function getName()
    {
        return '';
    }
}
