# This is my package laravel-forminertia

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ivanmercedes/laravel-forminertia.svg?style=flat-square)](https://packagist.org/packages/ivanmercedes/laravel-forminertia)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ivanmercedes/laravel-forminertia/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ivanmercedes/laravel-forminertia/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ivanmercedes/laravel-forminertia/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ivanmercedes/laravel-forminertia/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ivanmercedes/laravel-forminertia.svg?style=flat-square)](https://packagist.org/packages/ivanmercedes/laravel-forminertia)



## Installation

You can install the package via composer:

```bash
composer require ivanmercedes/laravel-forminertia
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-forminertia-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-forminertia-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-forminertia-views"
```

## Usage

 
## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ivan Mercedes](https://github.com/ivanmercedes)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
