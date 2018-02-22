# ingenico

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
$ composer require asanzred/ingenico
```

Add ServiceProvider in your `app.php` config file.

```php
// config/app.php
'providers' => [
    ...
    Asanzred\Ingenico\IngenicoServiceProvider::class,
]
```

and instead on aliases

```php
// config/app.php
'aliases' => [
    ...
    'Ingenico'         => Asanzred\Ingenico\Facade::class,
]
```

## Configuration

Publish the config by running:

``` bash
    php artisan vendor:publish --provider="Asanzred\Ingenico\IngenicoServiceProvider"
```
Then there must be a new ingenico.php in your main config directory
Edit this new file and set up the values with your Ingenico access data mostly. For the others, type the ones you want

## Usage

You can find the examples IngenicoController.php and routes.php
Copy the lines you want from this package routes.php to your main routes.php in order to try some payment examples.
Then, visit these new urls in your browser.
- http://YOURDOMAIN/ingenico/testconnection
- http://YOURDOMAIN/ingenico/example1
- http://YOURDOMAIN/ingenico/example2request
- http://YOURDOMAIN/ingenico/example3request


## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/badge/version-1.0.x--dev-orange.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-yellow.svg?style=flat-square
[ico-downloads]: https://img.shields.io/badge/downloads-20-lightgrey.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/asanzred/ingenico
[link-downloads]: https://packagist.org/packages/asanzred/ingenico
[link-author]: https://github.com/asanzred
[link-contributors]: ../../contributors

