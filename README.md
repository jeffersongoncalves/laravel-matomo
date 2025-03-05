<div class="filament-hidden">

![Laravel Matomo](https://raw.githubusercontent.com/jeffersongoncalves/laravel-matomo/master/art/jeffersongoncalves-laravel-matomo.png)

</div>

# Laravel Matomo

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-matomo.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-matomo)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-matomo/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-matomo/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-matomo.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-matomo)

A simple and elegant Laravel package that seamlessly integrates Matomo Analytics tracking code into your Blade views.

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-matomo
```

## Usage

Publish config file.

```bash
php artisan vendor:publish --tag=matomo-config
```

Add head template.

```php
@include('matomo::script')
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jèfferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
