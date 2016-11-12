<?php

namespace Gist\Form;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserRegisterForm.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class UserRegisterForm extends AbstractForm
{
    /**
     * {@inheritdoc}
     */
    public function build(array $options = array())
    {
        $this->builder->add(
            'username',
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
            'password',
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
}
