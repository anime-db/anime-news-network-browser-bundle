<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Tests\DependencyInjection;

use AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ScalarNode;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    protected function setUp()
    {
        $this->configuration = new Configuration();
    }

    public function testConfigTree()
    {
        $tree_builder = $this->configuration->getConfigTreeBuilder();

        $this->assertInstanceOf(TreeBuilder::class, $tree_builder);

        /* @var $tree ArrayNode */
        $tree = $tree_builder->buildTree();

        $this->assertInstanceOf(ArrayNode::class, $tree);
        $this->assertEquals('anime_db_anime_news_network_browser', $tree->getName());

        /* @var $children ScalarNode[] */
        $children = $tree->getChildren();

        $this->assertInternalType('array', $children);
        $this->assertEquals(['host', 'reports', 'details', 'client'], array_keys($children));

        $this->assertInstanceOf(ScalarNode::class, $children['host']);
        $this->assertEquals('https://cdn.animenewsnetwork.com', $children['host']->getDefaultValue());
        $this->assertFalse($children['host']->isRequired());

        $this->assertInstanceOf(ScalarNode::class, $children['reports']);
        $this->assertEquals('/encyclopedia/reports.xml', $children['reports']->getDefaultValue());
        $this->assertFalse($children['reports']->isRequired());

        $this->assertInstanceOf(ScalarNode::class, $children['details']);
        $this->assertEquals('/encyclopedia/api.xml', $children['details']->getDefaultValue());
        $this->assertFalse($children['details']->isRequired());

        $this->assertInstanceOf(ScalarNode::class, $children['client']);
        $this->assertEquals('', $children['client']->getDefaultValue());
        $this->assertFalse($children['client']->isRequired());
    }
}
