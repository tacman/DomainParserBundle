<?php

namespace EmanueleMinotto\DomainParserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * {@inheritdoc}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('domain_parser');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('cache_dir')
                    ->defaultNull()
                    ->info('Directory where text and php versions of list will be cached')
                ->end()
                ->scalarNode('http_adapter')
                    ->defaultNull()
                    ->info('HTTP adapter (must implement \Pdp\HttpAdapter\HttpAdapterInterface)')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
