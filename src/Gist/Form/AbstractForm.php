<?php

namespace Gist\Form;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Translation\Translator;

/**
 * Class AbstractForm
 * @author Simon Vieille <simon@deblan.fr>
 */
abstract class AbstractForm
{
    protected $builder;

    protected $translator;

    public function __construct(FormFactory $formFactory, Translator $translator, $data = null, $formFactoryOptions = array())
    {
        $this->translator = $translator;

        $this->builder = $formFactory->createNamedBuilder($this->getName(), 'form', $data, $formFactoryOptions);
    }

    public function getForm()
    {
        return $this->builder->getForm();
    }

    public function getName()
    {
        return 'form';
    }

    abstract public function build(array $options = array());
}
