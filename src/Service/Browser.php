<?php

/**
 * AnimeDb package.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\Service;

use GuzzleHttp\Client;

class Browser
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $reports;

    /**
     * @var string
     */
    private $details;

    /**
     * @var string
     */
    private $app_client;

    /**
     * @param Client $client
     * @param string $host
     * @param string $reports
     * @param string $details
     * @param string $app_client
     */
    public function __construct(Client $client, $host, $reports, $details, $app_client)
    {
        $this->client = $client;
        $this->host = $host;
        $this->reports = $reports;
        $this->details = $details;
        $this->app_client = $app_client;
    }

    /**
     * @param int   $id
     * @param array $options
     *
     * @return string
     */
    public function reports($id, array $options = [])
    {
        $options['id'] = $id;

        return $this->request($this->host.$this->reports, $options);
    }

    /**
     * @param array $options
     *
     * @return string
     */
    public function details(array $options)
    {
        return $this->request($this->host.$this->details, $options);
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return string
     */
    private function request($url, array $options)
    {
        if ($this->app_client) {
            $options['headers'] = array_merge(
                ['User-Agent' => $this->app_client],
                isset($options['headers']) ? $options['headers'] : []
            );
        }

        $response = $this->client->request('GET', $url, $options);

        return $response->getBody()->getContents();
    }
}
