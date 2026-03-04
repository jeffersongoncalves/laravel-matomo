---
name: matomo-development
description: Integrate and manage Matomo analytics tracking in Laravel applications using the jeffersongoncalves/laravel-matomo package with spatie/laravel-settings.
---

# Matomo Development

## When to use this skill

Use this skill when:
- Adding Matomo (formerly Piwik) analytics tracking to a Laravel application
- Managing Matomo tracking configuration via database settings
- Including the Matomo tracking script in Blade layouts
- Building admin interfaces to configure Matomo settings
- Working with the `MatomoSettings` class (spatie/laravel-settings)

## Setup

### Install the package

```bash
composer require jeffersongoncalves/laravel-matomo
```

### Publish and run settings migration

```bash
php artisan vendor:publish --tag=matomo-settings-migrations
php artisan migrate
```

This creates 5 settings in the `settings` database table under the `matomo` group.

### Requirements

- PHP 8.2+
- Laravel 11+
- `spatie/laravel-settings` ^3.0

## Package Structure

```
src/
  Settings/MatomoSettings.php    # Settings class with 5 tracking properties
  MatomoServiceProvider.php       # Service provider (auto-discovered)
resources/views/
  script.blade.php                # Matomo tracking JavaScript snippet
database/settings/
  2026_01_01_000000_create_matomo_settings.php  # Settings migration
```

## Settings Class

The `MatomoSettings` class extends `Spatie\LaravelSettings\Settings`:

```php
namespace JeffersonGoncalves\Matomo\Settings;

use Spatie\LaravelSettings\Settings;

class MatomoSettings extends Settings
{
    public string $domains;
    public string $site_id;
    public string $file;
    public string $script;
    public string $host_analytics;

    public static function group(): string
    {
        return 'matomo';
    }
}
```

### Settings properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `domains` | `string` | `''` | Tracked domains (empty disables tracking) |
| `site_id` | `string` | `'1'` | Matomo site ID number |
| `file` | `string` | `'matomo.php'` | Tracker endpoint filename |
| `script` | `string` | `'matomo.js'` | JavaScript tracker filename |
| `host_analytics` | `string` | `''` | Matomo server hostname (e.g., `analytics.example.com`) |

### Accessing settings

```php
use JeffersonGoncalves\Matomo\Settings\MatomoSettings;

// Via dependency injection (recommended -- singleton)
public function show(MatomoSettings $settings)
{
    return $settings->site_id;
}

// Via app container
$settings = app(MatomoSettings::class);
$host = $settings->host_analytics;
$siteId = $settings->site_id;
```

### Updating settings

```php
$settings = app(MatomoSettings::class);
$settings->domains = 'example.com';
$settings->host_analytics = 'analytics.example.com';
$settings->site_id = '1';
$settings->file = 'matomo.php';
$settings->script = 'matomo.js';
$settings->save();
```

## Blade View

The package provides a single Blade view `matomo::script` that renders the Matomo tracking code.

### Including in layout

```blade
<head>
    <!-- other head content -->
    @include('matomo::script')
</head>
```

### How the view works

The `MatomoServiceProvider` registers a view composer that injects `$settings` (MatomoSettings instance) into the `matomo::script` view automatically. The view checks if `$settings->domains` is non-empty before rendering.

When settings are configured, the output is:

```html
<!-- Matomo -->
<script>
    var _paq = window._paq = window._paq || [];
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function() {
        var u="//analytics.example.com/";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '1']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
    })();
</script>
<!-- End Matomo Code -->
```

### Key rendering logic

- **Condition**: Only renders when `$settings->domains` is not empty
- **Tracker URL**: Built from `$settings->host_analytics` + `$settings->file`
- **Script URL**: Built from `$settings->host_analytics` + `$settings->script`
- **Site ID**: From `$settings->site_id`
- **Features**: Automatic page view tracking and link tracking enabled by default

## Service Provider

The `MatomoServiceProvider` handles:

1. **View registration**: Registers views under the `matomo` namespace (`matomo::script`).
2. **Singleton binding**: Registers `MatomoSettings` as a singleton in the container.
3. **Settings class registration**: Merges `MatomoSettings::class` into `settings.settings` config array (with deduplication check).
4. **View composer**: Injects `$settings` into `matomo::script` automatically.
5. **Publishable migrations**: Tagged as `matomo-settings-migrations`.

## Integration with Filament

To manage Matomo settings via a Filament admin panel, create a settings page:

```php
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use JeffersonGoncalves\Matomo\Settings\MatomoSettings;

class ManageMatomoSettings extends SettingsPage
{
    protected static string $settings = MatomoSettings::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('domains')
                ->label('Tracked Domains')
                ->placeholder('example.com')
                ->helperText('Leave empty to disable tracking'),

            TextInput::make('host_analytics')
                ->label('Matomo Host')
                ->placeholder('analytics.example.com')
                ->required(),

            TextInput::make('site_id')
                ->label('Site ID')
                ->default('1')
                ->required(),

            TextInput::make('file')
                ->label('Tracker Endpoint')
                ->default('matomo.php')
                ->required(),

            TextInput::make('script')
                ->label('JavaScript File')
                ->default('matomo.js')
                ->required(),
        ]);
    }
}
```

## Configuration

This package has **no config file**. All configuration is stored in the database via `spatie/laravel-settings`.

| Setting | Type | Default | Description |
|---------|------|---------|-------------|
| `matomo.domains` | `string` | `''` | Tracked domains (empty = tracking disabled) |
| `matomo.site_id` | `string` | `'1'` | Matomo site ID |
| `matomo.file` | `string` | `'matomo.php'` | Tracker endpoint filename |
| `matomo.script` | `string` | `'matomo.js'` | JavaScript tracker filename |
| `matomo.host_analytics` | `string` | `''` | Matomo server hostname |

## Testing

The package uses Pest with Orchestra Testbench:

```bash
php vendor/bin/pest
```

### Testing patterns

```php
use JeffersonGoncalves\Matomo\Settings\MatomoSettings;

it('renders matomo script when domains is set', function () {
    $settings = app(MatomoSettings::class);
    $settings->domains = 'example.com';
    $settings->host_analytics = 'analytics.example.com';
    $settings->site_id = '1';
    $settings->file = 'matomo.php';
    $settings->script = 'matomo.js';
    $settings->save();

    $view = $this->blade("@include('matomo::script')");

    $view->assertSee('analytics.example.com');
    $view->assertSee('setSiteId');
});

it('does not render when domains is empty', function () {
    $settings = app(MatomoSettings::class);
    $settings->domains = '';
    $settings->save();

    $view = $this->blade("@include('matomo::script')");

    $view->assertDontSee('_paq');
});
```

## Publish Tags

| Tag | Description |
|-----|-------------|
| `matomo-settings-migrations` | Settings migration to `database/settings/` |
