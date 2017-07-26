<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Tests\Service;

use AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Exception\NotFoundException;
use AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Service\ErrorDetector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ErrorDetectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ResponseInterface
     */
    private $response;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StreamInterface
     */
    private $stream;

    /**
     * @var ErrorDetector
     */
    private $detector;

    protected function setUp()
    {
        $this->response = $this->getMock(ResponseInterface::class);
        $this->stream = $this->getMock(StreamInterface::class);

        $this->detector = new ErrorDetector();
    }

    /**
     * @expectedException \AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Exception\NotFoundException
     */
    public function testStatusNotFound()
    {
        $this->response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(404))
        ;

        $this->detector->detect($this->response);
    }

    public function testNoErrors()
    {
        $content = 'foo';

        $this->response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200))
        ;
        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($this->stream))
        ;

        $this->stream
            ->expects($this->once())
            ->method('getContents')
            ->will($this->returnValue($content))
        ;

        $this->assertEquals($content, $this->detector->detect($this->response));
    }

    /**
     * @return array
     */
    public function noResults()
    {
        return [
            [
                '<ann><warning>no result for title=1</warning></ann>',
                'Title with id "1" not found.',
            ],
            [
                '<ann><warning>no result for manga=1</warning></ann>',
                'Manga with id "1" not found.',
            ],
            [
                '<ann><warning>no result for anime=1</warning></ann>',
                'Anime with id "1" not found.',
            ],
            [
                '<ann><warning>no result for anime=foo</warning></ann>',
                'Anime with id "foo" not found.',
            ],
            [
                '<ann><warning>no result for anime=</warning></ann>',
                'Anime with id "" not found.',
            ],
        ];
    }

    /**
     * @dataProvider noResults
     *
     * @param string $content
     * @param string $message
     */
    public function testNoResult($content, $message)
    {
        $this->response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200))
        ;
        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($this->stream))
        ;

        $this->stream
            ->expects($this->once())
            ->method('getContents')
            ->will($this->returnValue($content))
        ;

        $this->setExpectedException(NotFoundException::class, $message);

        $this->detector->detect($this->response);
    }

    /**
     * @expectedException \AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Exception\ErrorException
     * @expectedExceptionMessage You are banned
     */
    public function testCustomError()
    {
        $content = '<ann><warning>You are banned</warning></ann>';

        $this->response
            ->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(200))
        ;
        $this->response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($this->stream))
        ;

        $this->stream
            ->expects($this->once())
            ->method('getContents')
            ->will($this->returnValue($content))
        ;

        $this->detector->detect($this->response);
    }
}
