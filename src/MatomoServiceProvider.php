<?php

namespace JeffersonGoncalves\Matomo;

use Illuminate\Support\Facades\View;
use JeffersonGoncalves\Matomo\Settings\MatomoSettings;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MatomoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-matomo')
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(MatomoSettings::class);
    }

    public function packageBooted(): void
    {
        $this->registerSettingsMigrations();
        $this->registerSettingsClass();
        $this->registerViewComposer();
    }

    protected function registerSettingsMigrations(): void
    {
        $this->publishes([
            __DIR__.'/../database/settings' => database_path('settings'),
        ], 'matomo-settings-migrations');
    }

    protected function registerSettingsClass(): void
    {
        $settings = config('settings.settings', []);

        if (! in_array(MatomoSettings::class, $settings)) {
            config(['settings.settings' => array_merge($settings, [MatomoSettings::class])]);
        }
    }

    protected function registerViewComposer(): void
    {
        View::composer('matomo::script', function ($view) {
            $view->with('settings', app(MatomoSettings::class));
        });
    }
}
