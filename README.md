# Search the Musixmatch API

[![Latest Stable Version](https://poser.pugx.org/atomescrochus/laravel-musixmatch/v/stable)](https://packagist.org/packages/atomescrochus/laravel-musixmatch)
[![License](https://poser.pugx.org/atomescrochus/laravel-musixmatch/license)](https://packagist.org/packages/atomescrochus/laravel-musixmatch)
[![Total Downloads](https://poser.pugx.org/atomescrochus/laravel-musixmatch/downloads)](https://packagist.org/packages/atomescrochus/laravel-musixmatch)

The `atomescrochus/laravel-musixmatch` package provide and easy way to search the Deezer Web API from any Laravel >= 5.3 application.

This package is **NOT** usable in production, and should still be considered a work in progress (contribution welcomed!). It requires PHP >= `7.0`.

## Install

You can install this package via composer:

``` bash
$ composer require atomescrochus/laravel-musixmatch
```

Then you have to install the package' service provider and alias:

```php
// config/app.php
'providers' => [
    ...
    Atomescrochus\Musixmatch\MusixmatchServiceProvider::class,
];
```

## Usage

``` php
// here is an example query to search Deezer's API
$deezer = new \Atomescrochus\Musixmatch\Musixmatch();
```

### Results
 
In the example above, what is returned in `$results` is an object containing: a collection of results; a count value for the results; raw response data; and the unformatted query sent to the API.

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
