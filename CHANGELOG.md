# Changelog

All notable changes to this project will be documented in this file.

## v2.0.1 - 2026-02-23

### Fixed

- Add datetime prefix to settings migration filename

## v2.0.0 - 2026-02-22

### Breaking Changes

- **Config file removed** — `config/matomo.php` no longer exists. All settings are now managed via `spatie/laravel-settings` (database-backed).
- **No more `.env` variables** — `MATOMO_FILE_PHP`, `MATOMO_FILE_JS`, `MATOMO_HOST_ANALYTICS` env vars are no longer read.

### What's New

- **Database-backed settings** via [`spatie/laravel-settings`](https://github.com/spatie/laravel-settings) — change Matomo configuration at runtime (e.g., from an admin panel) without redeployment.
- **`MatomoSettings` class** with typed properties: `domains`, `site_id`, `file`, `script`, `host_analytics`.
- **Dynamic `site_id`** — previously hardcoded as `'1'`, now configurable via settings.
- **Fixed missing `domains` config** — the view referenced `config('matomo.domains')` which didn't exist in the config file.
- **Updated defaults** — `matomo.php`/`matomo.js` instead of legacy `piwik.php`/`piwik.js`.

### Upgrade Guide

1. Update the package:
   
   ```bash
   composer require jeffersongoncalves/laravel-matomo:^2.0
   
   
   ```
2. Publish the settings migration:
   
   ```bash
   php artisan vendor:publish --tag=matomo-settings-migrations
   
   
   ```
3. Run the migration:
   
   ```bash
   php artisan migrate
   
   
   ```
4. Remove old config file if published:
   
   ```bash
   rm config/matomo.php
   
   
   ```
5. Configure settings via code:
   
   ```php
   use JeffersonGoncalves\Matomo\Settings\MatomoSettings;
   
   $settings = app(MatomoSettings::class);
   $settings->domains = 'example.com';
   $settings->site_id = '1';
   $settings->host_analytics = 'analytics.example.com';
   $settings->save();
   
   
   ```

**Full Changelog**: https://github.com/jeffersongoncalves/laravel-matomo/compare/v1.0.1...v2.0.0

## v1.0.1 - 2025-03-05

**Full Changelog**: https://github.com/jeffersongoncalves/laravel-matomo/compare/v1.0.0...v1.0.1

## v1.0.0 - 2025-03-05

**Full Changelog**: https://github.com/jeffersongoncalves/laravel-matomo/commits/v1.0.0
