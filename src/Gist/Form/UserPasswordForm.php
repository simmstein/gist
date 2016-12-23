<?php

namespace Gist\Form;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserPasswordForm.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class UserPasswordForm extends AbstractForm
{
    /**
     * {@inheritdoc}
     */
    public function build(array $options = array())
    {
        $this->builder->add(
            'currentPassword',
            'password',
            array(
                'required' => true,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => $this->translator->trans('login.register.form.current_password.placeholder'),
                ),
                'trim' => false,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => $this->translator->trans('form.error.not_blank'),
                    )),
                ),
            )
        );

        $this->builder->add(
            'newPassword',
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

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'password';
    }
}
