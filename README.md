# Search the Musixmatch API with cache support

[![Latest Stable Version](https://poser.pugx.org/atomescrochus/laravel-musixmatch/v/stable)](https://packagist.org/packages/atomescrochus/laravel-musixmatch)
[![License](https://poser.pugx.org/atomescrochus/laravel-musixmatch/license)](https://packagist.org/packages/atomescrochus/laravel-musixmatch)
[![Total Downloads](https://poser.pugx.org/atomescrochus/laravel-musixmatch/downloads)](https://packagist.org/packages/atomescrochus/laravel-musixmatch)

The `atomescrochus/laravel-musixmatch` package provide and easy way to search the Musixmatch API from any Laravel >= 5.3 application.

This package is **NOT** usable in production, and should still be considered a work in progress (_contribution welcomed!_). It requires PHP >= `7.0`.

## Install

You can install this package via composer:

``` bash
$ composer require atomescrochus/laravel-musixmatch
```

Then you have to install the package' service provider, _unless you are running Laravel >=5.5_ (it'll use package auto-discovery) :

```php
// config/app.php
'providers' => [
    ...
    Atomescrochus\Musixmatch\MusixmatchServiceProvider::class,
];
```

You will have to publish the configuration files also if you want to change the default value:
```bash
php artisan vendor:publish --provider="Atomescrochus\Musixmatch\MusixmatchServiceProvider" --tag="config"
```

You are also required to put the values you can fetch in your Gracenote developer account in your .env files:

```
MUSIXMATCH_APIKEY=12345678
```

## Usage

``` php
// here is an example query to search Musixmatch API
$search = new \Atomescrochus\Musixmatch\Musixmatch();
$results = $search->trackSearch('poker face', 'lady gaga')->results();

// or if you already have the track id and wants the lyrics
$lyrics = new \Atomescrochus\Musixmatch\Musixmatch();
$results = $lyrics->getLyrics(15476784)->results();

// you can change the default cache duration (of one day) like so
$lyrics->setCacheDuration(60); // in minutes

// If you ever find yourself that you want to check your local cache for a result, without actually polling the API:
$results = $lyrics->cacheOnly()->getLyrics(15476784)->results();

// In case you only want to check if the query exists in the cache, without actually pulling the data:
$existInCache = $lyrics->inCache()->getLyrics(15476784)->results(); // returns boolean
```

### Results
 
In the example above, what is returned in `$results` is an object containing: a collection of results; a count value for the results; raw response data; cache information, and the unformatted query sent to the API.

## Tests

This is basically a wrapper around the API. We're assuming the Musixmatch API has been tested, and we won't make duplicated tests.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email jp@atomescroch.us instead of using the issue tracker.

## Credits

- [Jean-Philippe Murray](https://github.com/jpmurray)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
