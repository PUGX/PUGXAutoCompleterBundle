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
}
