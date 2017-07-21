<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Tests\Service;

use AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Service\Browser;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class BrowserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $host = 'example.org';

    /**
     * @var string
     */
    private $reports = '/encyclopedia/reports.xml';

    /**
     * @var string
     */
    private $details = '/encyclopedia/api.xml';

    /**
     * @var string
     */
    private $app_client = 'My Custom Bot 1.0';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|HttpClient
     */
    private $client;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StreamInterface
     */
    private $stream;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|MessageInterface
     */
    private $message;

    /**
     * @var Browser
     */
    private $browser;

    protected function setUp()
    {
        $this->client = $this->getMock(HttpClient::class);
        $this->stream = $this->getMock(StreamInterface::class);
        $this->message = $this->getMock(MessageInterface::class);

        $this->browser = new Browser($this->client, $this->host, $this->reports, $this->details, $this->app_client);
    }

    /**
     * @return array
     */
    public function appClients()
    {
        return [
            [''],
            ['Override User Agent'],
        ];
    }

    /**
     * @dataProvider appClients
     *
     * @param string $app_client
     */
    public function testReports($app_client)
    {
        $id = 155;
        $params = ['bar' => 'baz'];
        $options = $params + [
            'id' => $id,
            'headers' => [
                'User-Agent' => $this->app_client,
            ],
        ];

        if ($app_client) {
            $options['headers']['User-Agent'] = $app_client;
            $params['headers']['User-Agent'] = $app_client;
        }

        $content = '<?xml version="1.0"?><root><text>Hello, world!</text></root>';

        $this->stream
            ->expects($this->once())
            ->method('getContents')
            ->will($this->returnValue($content))
        ;

        $this->message
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($this->stream))
        ;

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', $this->host.$this->reports, $options)
            ->will($this->returnValue($this->message))
        ;

        $this->assertEquals($content, $this->browser->reports($id, $params));
    }

    /**
     * @dataProvider appClients
     *
     * @param string $app_client
     */
    public function testDetails($app_client)
    {
        $params = ['bar' => 'baz'];
        $options = $params + [
                'headers' => [
                    'User-Agent' => $this->app_client,
                ],
            ];

        if ($app_client) {
            $options['headers']['User-Agent'] = $app_client;
            $params['headers']['User-Agent'] = $app_client;
        }

        $content = '<?xml version="1.0"?><root><text>Hello, world!</text></root>';

        $this->stream
            ->expects($this->once())
            ->method('getContents')
            ->will($this->returnValue($content))
        ;

        $this->message
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($this->stream))
        ;

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', $this->host.$this->details, $options)
            ->will($this->returnValue($this->message))
        ;

        $this->assertEquals($content, $this->browser->details($params));
    }
}
