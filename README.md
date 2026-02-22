<div class="filament-hidden">

![Laravel Matomo](https://raw.githubusercontent.com/jeffersongoncalves/laravel-matomo/master/art/jeffersongoncalves-laravel-matomo.png)

</div>

# Laravel Matomo

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-matomo.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-matomo)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-matomo/fix-php-code-style-issues.yml?branch=master&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-matomo/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-matomo.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-matomo)

A simple and elegant Laravel package that seamlessly integrates Matomo Analytics tracking code into your Blade views. Settings are stored in the database via [spatie/laravel-settings](https://github.com/spatie/laravel-settings), allowing runtime configuration without `.env` files.

## Installation

Install the package via Composer:

```bash
composer require jeffersongoncalves/laravel-matomo
```

Publish the settings migration:

```bash
php artisan vendor:publish --tag=matomo-settings-migrations
```

Run the migration:

```bash
php artisan migrate
```

## Usage

Add the tracking script to your Blade layout (typically before `</head>`):

```php
@include('matomo::script')
```

### Configuration

All settings are stored in the database. You can update them via code:

```php
use JeffersonGoncalves\Matomo\Settings\MatomoSettings;

$settings = app(MatomoSettings::class);

$settings->domains = 'example.com';
$settings->site_id = '1';
$settings->host_analytics = 'analytics.example.com';
$settings->file = 'matomo.php';
$settings->script = 'matomo.js';

$settings->save();
```

### Available Settings

| Setting | Default | Description |
|---|---|---|
| `domains` | `''` | Domain(s) for tracking |
| `site_id` | `'1'` | Site ID in Matomo |
| `host_analytics` | `''` | Matomo server URL (without protocol) |
| `file` | `'matomo.php'` | PHP tracking file |
| `script` | `'matomo.js'` | JS tracking file |

## Filament Integration

For [Filament](https://filamentphp.com) users, install the companion plugin [`jeffersongoncalves/filament-matomo`](https://github.com/jeffersongoncalves/filament-matomo) to manage Matomo settings directly from your admin panel with a dedicated Settings Page:

```bash
composer require jeffersongoncalves/filament-matomo
```

See the [filament-matomo documentation](https://github.com/jeffersongoncalves/filament-matomo) for setup instructions.

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

- [Jefferson Goncalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
