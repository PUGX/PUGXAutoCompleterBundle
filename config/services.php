<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteFilterType;
use PUGX\AutocompleterBundle\Listener\FilterSubscriber;

return static function (ContainerConfigurator $container): void {
    $parameters = $container->parameters();

    $parameters->set('pugx_autocompleter.autocomplete_class', AutocompleteType::class);
    $parameters->set('pugx_autocompleter.autocomplete_filter_class', AutocompleteFilterType::class);

    $services = $container->services();

    $services
        ->set('pugx_autocompleter.autocomplete')
        ->class('%pugx_autocompleter.autocomplete_class%')
        ->public()
        ->tag('form.type', ['alias' => 'autocomplete'])
    ;

    $services
        ->set('pugx_autocompleter.filter_autocomplete')
        ->class('%pugx_autocompleter.autocomplete_filter_class%')
        ->public()
        ->tag('form.type', ['alias' => 'filter_autocomplete'])
    ;

    $services
        ->set('pugx_autocompleter.filter.doctrine_subscriber')
        ->class(FilterSubscriber::class)
        ->tag('kernel.event_subscriber')
    ;
};
