<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Exception;

class NotFoundException extends ErrorException
{
    /**
     * @param $source $id
     * @param string $id
     *
     * @return NotFoundException
     */
    public static function source($source, $id)
    {
        return new self(sprintf('%s with id "%s" not found.', ucfirst($source), $id));
    }

    /**
     * @return NotFoundException
     */
    public static function page()
    {
        return new self('Page not found.');
    }
}
