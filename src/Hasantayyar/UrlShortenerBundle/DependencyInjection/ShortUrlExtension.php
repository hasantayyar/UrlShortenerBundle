<?php

namespace Hasantayyar\UrlShortenerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ShortUrlExtension extends Extension
{
    /**
     * {@inheritDoc}
     */

    /**
     * @param array                                                   $configs   Array of configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container Container instance
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $shortener = $container->getDefinition('url_shortener.shortener_' . $config['shortener']);
        $shortener->addMethodCall('setShortCodeLength', array($config['shortCodeLength']));

        $arguments = $container->getDefinition('url_shortener.shortener')->getArguments();

        $arguments[0] = $shortener;

        $container->getDefinition('url_shortener.shortener')->setArguments($arguments);
    }

}
