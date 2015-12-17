<?php

namespace PUGX\AutocompleterBundle\Form\Type;

class AutocompleteFilterType extends AutocompleteType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_autocomplete';
    }
}
