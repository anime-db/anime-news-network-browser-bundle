<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Service;

use AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Exception\NotFoundException;
use AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Exception\ErrorException;
use Psr\Http\Message\ResponseInterface;

class ErrorDetector
{
    /**
     * @param ResponseInterface $response
     *
     * @return string
     */
    public function detect(ResponseInterface $response)
    {
        if ($response->getStatusCode() == 404) {
            throw NotFoundException::page();
        }

        $content = $response->getBody()->getContents();

        // no warnings
        if (strpos($content, '<ann><warning>') === false) {
            return $content;
        }

        if (preg_match('/<warning>no result for (title|manga|anime)=([^<]*)<\/warning>/i', $content, $match)) {
            switch ($match[1]) {
                case 'title':
                    throw NotFoundException::title($match[2]);
                    break;
                case 'manga':
                    throw NotFoundException::manga($match[2]);
                    break;
                case 'anime':
                    throw NotFoundException::anime($match[2]);
                    break;
            }
        }

        throw ErrorException::error(preg_replace('/.*<warning>(.*)<\/warning>.*/im', '$1', $content));
    }
}
