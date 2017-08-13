<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Config tree builder.
     *
     * Example config:
     *
     * anime_db_anime_news_network_browser:
     *     host: 'https://cdn.animenewsnetwork.cc'
     *     reports: '/encyclopedia/reports.xml'
     *     details: '/encyclopedia/api.xml'
     *     client: 'My Custom Bot 1.0'
     *
     * @return ArrayNodeDefinition
     */
    public function getConfigTreeBuilder()
    {
        return (new TreeBuilder())
            ->root('anime_db_anime_news_network_browser')
                ->children()
                    ->scalarNode('host')
                        ->defaultValue('https://cdn.animenewsnetwork.cc')
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('reports')
                        ->defaultValue('/encyclopedia/reports.xml')
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('details')
                        ->defaultValue('/encyclopedia/api.xml')
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('client')
                        ->defaultValue('')
                        ->cannotBeEmpty()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
