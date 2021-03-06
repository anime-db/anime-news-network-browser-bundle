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
use AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Service\ErrorDetector;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;

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
     * @var \PHPUnit_Framework_MockObject_MockObject|ResponseInterface
     */
    private $response;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ErrorDetector
     */
    private $detector;

    /**
     * @var Browser
     */
    private $browser;

    protected function setUp()
    {
        $this->client = $this->getMock(HttpClient::class);
        $this->response = $this->getMock(ResponseInterface::class);
        $this->detector = $this->getMock(ErrorDetector::class);

        $this->browser = new Browser(
            $this->client,
            $this->detector,
            $this->host,
            $this->reports,
            $this->details,
            $this->app_client
        );
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
        $params = ['timeout' => 5];
        $options = $params + [
            'query' => [
                'id' => $id,
            ],
            'headers' => [
                'User-Agent' => $this->app_client,
            ],
        ];

        if ($app_client) {
            $options['headers']['User-Agent'] = $app_client;
            $params['headers']['User-Agent'] = $app_client;
        }

        $content = '<?xml version="1.0"?><root><text>Hello, world!</text></root>';

        $this->detector
            ->expects($this->once())
            ->method('detect')
            ->with($this->response)
            ->will($this->returnValue($content))
        ;

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', $this->host.$this->reports, $options)
            ->will($this->returnValue($this->response))
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
        $params = ['timeout' => 5];
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

        $this->detector
            ->expects($this->once())
            ->method('detect')
            ->with($this->response)
            ->will($this->returnValue($content))
        ;

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', $this->host.$this->details, $options)
            ->will($this->returnValue($this->response))
        ;

        $this->assertEquals($content, $this->browser->details($params));
    }
}
