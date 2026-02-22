<?php

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
