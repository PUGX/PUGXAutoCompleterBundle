<?php

namespace PUGX\AutocompleterBundle\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use PUGX\AutocompleterBundle\Form\Transformer\ObjectToIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AutocompleteFilterType extends AutocompleteType
{
    public function getName()
    {
        return 'filter_autocomplete';
    }
}
