## Laravel Matomo Analytics

The `jeffersongoncalves/laravel-matomo` package integrates Matomo (formerly Piwik) analytics tracking into Laravel applications using `spatie/laravel-settings` for database-driven configuration.

### Overview

- **Namespace**: `JeffersonGoncalves\Matomo`
- **Service Provider**: `MatomoServiceProvider` (auto-discovered)
- **View**: `matomo::script` (tracking code snippet)
- **Settings**: `MatomoSettings` registered as singleton

### Key Concepts

- All settings are stored in the database via `spatie/laravel-settings`, not in config files.
- The `MatomoSettings` class has 5 properties: `domains`, `site_id`, `file`, `script`, `host_analytics`.
- The `matomo::script` view only renders when `domains` is non-empty.
- A view composer automatically injects `$settings` into the `matomo::script` view.

### Settings (spatie/laravel-settings)

The `MatomoSettings` class extends `Spatie\LaravelSettings\Settings` with group `matomo`:

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `domains` | `string` | `''` | Tracked domains (enables/disables tracking) |
| `site_id` | `string` | `'1'` | Matomo site ID |
| `file` | `string` | `'matomo.php'` | Tracker endpoint filename |
| `script` | `string` | `'matomo.js'` | JavaScript tracker filename |
| `host_analytics` | `string` | `''` | Matomo server hostname (e.g., `analytics.example.com`) |

@verbatim
<code-snippet name="Accessing MatomoSettings" lang="php">
use JeffersonGoncalves\Matomo\Settings\MatomoSettings;

// Via dependency injection
public function __construct(private MatomoSettings $settings) {}

// Via app container (singleton)
$settings = app(MatomoSettings::class);
$siteId = $settings->site_id;
$host = $settings->host_analytics;
</code-snippet>
@endverbatim

### Configuration

No config file is used. All settings are database-driven via `spatie/laravel-settings`.

Publish the settings migration:

@verbatim
<code-snippet name="Publishing migrations" lang="bash">
php artisan vendor:publish --tag=matomo-settings-migrations
php artisan migrate
</code-snippet>
@endverbatim

### Blade Integration

Include the tracking script in your layout (typically before `</head>` or before `</body>`):

@verbatim
<code-snippet name="Layout integration" lang="blade">
<head>
    @include('matomo::script')
</head>
</code-snippet>
@endverbatim

### Updating Settings

@verbatim
<code-snippet name="Updating Matomo settings" lang="php">
use JeffersonGoncalves\Matomo\Settings\MatomoSettings;

$settings = app(MatomoSettings::class);
$settings->domains = 'example.com';
$settings->host_analytics = 'analytics.example.com';
$settings->site_id = '1';
$settings->file = 'matomo.php';
$settings->script = 'matomo.js';
$settings->save();
</code-snippet>
@endverbatim

### Conventions

- The `MatomoSettings` group is `matomo` -- all database settings are prefixed with `matomo.`.
- The service provider registers `MatomoSettings` as a singleton.
- The service provider registers `MatomoSettings` into `settings.settings` config (deduplication check).
- A view composer injects `$settings` into `matomo::script` automatically.
- The `matomo::script` view only renders when `$settings->domains` is non-empty.
- The tracking code uses `_paq` array (Matomo standard) with `trackPageView` and `enableLinkTracking`.
- The tracker URL is built from `host_analytics` + `file`, and the script URL from `host_analytics` + `script`.
