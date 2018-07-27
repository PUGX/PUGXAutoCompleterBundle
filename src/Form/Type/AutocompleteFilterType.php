<?php

namespace PUGX\AutocompleterBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class AutocompleteFilterType extends AutocompleteType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'required' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'filter_autocomplete';
    }
}
