<?php

namespace PUGX\AutocompleterBundle\Form\Type;

class AutocompleteFilterType extends AutocompleteType
{
    public function getBlockPrefix(): string
    {
        return 'filter_autocomplete';
    }
}
