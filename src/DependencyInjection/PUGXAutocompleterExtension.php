<?php

namespace PUGX\AutocompleterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * This is the class that loads and manages bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/bundles/extension.html}
 *
 * @codeCoverageIgnore
 */
final class PUGXAutocompleterExtension extends ConfigurableExtension
{
    /**
     * @param array<string, mixed> $configs
     *
     * @throws \Exception
     */
    public function loadInternal(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.xml');
    }
}
