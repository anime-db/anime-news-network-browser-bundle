[![AnimeNewsNetwork.com](http://www.animenewsnetwork.com/stylesheets/img/logo.name.no-dot.png)](http://www.animenewsnetwork.com)

[![Latest Stable Version](https://img.shields.io/packagist/v/anime-db/anime-news-network-browser-bundle.svg?maxAge=3600&label=stable)](https://packagist.org/packages/anime-db/anime-news-network-browser-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/anime-db/anime-news-network-browser-bundle.svg?maxAge=3600)](https://packagist.org/packages/anime-db/anime-news-network-browser-bundle)
[![Build Status](https://img.shields.io/travis/anime-db/anime-news-network-browser-bundle.svg?maxAge=3600)](https://travis-ci.org/anime-db/anime-news-network-browser-bundle)
[![Coverage Status](https://img.shields.io/coveralls/anime-db/anime-news-network-browser-bundle.svg?maxAge=3600)](https://coveralls.io/github/anime-db/anime-news-network-browser-bundle?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/anime-db/anime-news-network-browser-bundle.svg?maxAge=3600)](https://scrutinizer-ci.com/g/anime-db/anime-news-network-browser-bundle/?branch=master)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/f777bb9e-3b51-4c93-8d74-0e4f652db1c9.svg?maxAge=3600&label=SLInsight)](https://insight.sensiolabs.com/projects/f777bb9e-3b51-4c93-8d74-0e4f652db1c9)
[![StyleCI](https://styleci.io/repos/97733459/shield?branch=master)](https://styleci.io/repos/97733459)
[![License](https://img.shields.io/packagist/l/anime-db/anime-news-network-browser-bundle.svg?maxAge=3600)](https://github.com/anime-db/anime-news-network-browser-bundle)

AnimeNewsNetwork.com API browser
================================

Installation
------------

Pretty simple with [Composer](http://packagist.org), run:

```sh
composer require anime-db/anime-news-network-browser-bundle
```

Add AnimeDbAnimeNewsNetworkBrowserBundle to your application kernel

```php
// app/appKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new AnimeDb\Bundle\AnimeNewsNetworkBrowserBundle\AnimeDbAnimeNewsNetworkBrowserBundle(),
    );
}
```

Configuration
-------------

```yml
anime_db_anime_news_network_browser:
    # Host name
    # As a default used 'https://cdn.animenewsnetwork.com'
    host: 'https://cdn.animenewsnetwork.com'

    # Reports
    # As a default used '/encyclopedia/reports.xml'
    reports: '/encyclopedia/reports.xml'

    # Anime/Manga Details
    # As a default used '/encyclopedia/api.xml'
    details: '/encyclopedia/api.xml'

    # HTTP User-Agent
    # No default value
    client: 'My Custom Bot 1.0'
```

Usage
-----

First get browser

```php
$browser = $this->get('anime_db.anime_news_network.browser');
```

License
-------

This bundle is under the [GPL v3 license](http://opensource.org/licenses/GPL-3.0).
See the complete license in the file: LICENSE
