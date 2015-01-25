<?php

namespace CL\Bundle\MailerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('cl_mailer');

        $rootNode->children()
            ->booleanNode('twig')->defaultTrue()->end()
            ->arrayNode('types')
                ->prototype('array')
                    ->children()
                        ->scalarNode('from_email')->end()
                        ->scalarNode('from_name')->end()
                        ->scalarNode('to_email')->end()
                        ->scalarNode('to_name')->end()
                        ->scalarNode('subject')->end()
                        ->scalarNode('template')->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('defaults')
                ->children()
                    ->scalarNode('from_email')->end()
                    ->scalarNode('from_name')->end()
                    ->scalarNode('to_email')->end()
                    ->scalarNode('to_name')->end()
                    ->scalarNode('subject')->end()
                    ->scalarNode('html_layout')->defaultValue('CLMailerBundle:mailer:layout.html.twig')->end()
                    ->scalarNode('plain_text_layout')->defaultValue('CLMailerBundle:mailer:layout.txt.twig')->end()
                    ->scalarNode('stylesheet')->defaultValue('CLMailerBundle:mailer:stylesheet.css.twig')->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
