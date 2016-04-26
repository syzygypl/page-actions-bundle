<?php

namespace ArsThanea\PageActionsBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('page_actions');

        /** @var ArrayNodeDefinition $resource */
        $resource = $root->children()->arrayNode('resources')->useAttributeAsKey('name')->prototype('array');

        $resource->children()->scalarNode('resource')->isRequired();
        $resource->children()->scalarNode('type')->defaultNull();

        return $treeBuilder;
    }
}
