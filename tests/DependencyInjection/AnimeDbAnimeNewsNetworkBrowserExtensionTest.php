<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Tests\DependencyInjection;

use AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\DependencyInjection\AnimeDbAnimeNewsNetworkBrowserExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AnimeDbAnimeNewsNetworkBrowserExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ContainerBuilder
     */
    private $container;

    /**
     * @var AnimeDbAnimeNewsNetworkBrowserExtension
     */
    private $extension;

    protected function setUp()
    {
        $this->container = $this->getMock(ContainerBuilder::class);
        $this->extension = new AnimeDbAnimeNewsNetworkBrowserExtension();
    }

    /**
     * @return array
     */
    public function config()
    {
        return [
            [
                [],
                'https://cdn.animenewsnetwork.cc',
                '/encyclopedia/reports.xml',
                '/encyclopedia/api.xml',
                '',
            ],
            [
                [
                    'anime_db_anime_news_network_browser' => [
                        'host' => 'http://cdn.animenewsnetwork.cc',
                        'reports' => '/encyclopedia/reports.json',
                        'details' => '/encyclopedia/api.json',
                        'client' => 'My Custom Bot 1.0',
                    ],
                ],
                'http://cdn.animenewsnetwork.cc',
                '/encyclopedia/reports.json',
                '/encyclopedia/api.json',
                'My Custom Bot 1.0',
            ],
        ];
    }

    /**
     * @dataProvider config
     *
     * @param array  $config
     * @param string $host
     * @param string $reports
     * @param string $details
     * @param string $client
     */
    public function testLoad(array $config, $host, $reports, $details, $client)
    {
        $browser = $this->getMock(Definition::class);
        $browser
            ->expects($this->at(0))
            ->method('replaceArgument')
            ->with(2, $host)
            ->will($this->returnSelf())
        ;
        $browser
            ->expects($this->at(1))
            ->method('replaceArgument')
            ->with(3, $reports)
            ->will($this->returnSelf())
        ;
        $browser
            ->expects($this->at(2))
            ->method('replaceArgument')
            ->with(4, $details)
            ->will($this->returnSelf())
        ;
        $browser
            ->expects($this->at(3))
            ->method('replaceArgument')
            ->with(5, $client)
            ->will($this->returnSelf())
        ;

        $this->container
            ->expects($this->once())
            ->method('getDefinition')
            ->with('anime_db.anime_news_network.browser')
            ->will($this->returnValue($browser))
        ;

        $this->extension->load($config, $this->container);
    }
}
