<?php

namespace Hasantayyar\UrlShortenerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritDoc}
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('url_shortener');

        $rootNode
                ->addDefaultsIfNotSet()
                ->children()
                ->scalarNode('shortener')->defaultValue('base')->end()
                ->scalarNode('shortCodeLength')->defaultValue(3)->end()
                ->end();

        return $treeBuilder;
    }

}
