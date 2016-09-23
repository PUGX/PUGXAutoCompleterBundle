<?php

namespace PUGX\AutocompleterBundle\Form\Type;

class AutocompleteFilterType extends AutocompleteType
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'filter_autocomplete';
    }

    /**
     * BC for Symfony 2.7.
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
