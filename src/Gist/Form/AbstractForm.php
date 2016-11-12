<?php

namespace Gist\Form;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Translation\Translator;

/**
 * Class AbstractForm.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
abstract class AbstractForm
{
    /**
     * The builder.
     *
     * @var Symfony\Component\Form\FormBuilder
     */
    protected $builder;

    /**
     * The translator.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * __construct.
     *
     * @param FormFactory $formFactory
     * @param Translator  $translator
     * @param mixed       $data
     * @param array       $formFactoryOptions
     */
    public function __construct(FormFactory $formFactory, Translator $translator, $data = null, $formFactoryOptions = array())
    {
        $this->translator = $translator;

        $this->builder = $formFactory->createNamedBuilder($this->getName(), 'form', $data, $formFactoryOptions);
    }

    /**
     * Returns the form from the builder.
     *
     * @return Symfony\Component\Form\Form
     */
    public function getForm()
    {
        return $this->builder->getForm();
    }

    /**
     * Returns the form's name.
     *
     * @return string
     */
    public function getName()
    {
        return 'form';
    }

    /**
     * Builds the form.
     *
     * @param array $options
     *
     * @return Symfony\Component\Form\FormBuilder
     */
    abstract public function build(array $options = array());
}
