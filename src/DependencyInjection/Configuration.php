<?php

namespace PUGX\AutocompleterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/bundles/extension.html#manually-registering-an-extension-class}
 *
 * @codeCoverageIgnore
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('pugx_autocompleter');

        return $treeBuilder;
    }
}
