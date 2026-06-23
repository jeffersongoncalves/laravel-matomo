<?php

namespace JeffersonGoncalves\Matomo\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\Matomo\MatomoServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelSettings\LaravelSettingsServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelSettingsServiceProvider::class,
            MatomoServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase(): void
    {
        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function ($table) {
                $table->id();
                $table->string('group');
                $table->string('name');
                $table->boolean('locked')->default(false);
                $table->json('payload');
                $table->timestamps();

                $table->unique(['group', 'name']);
            });
        }

        $this->seedSettings();
    }

    /**
     * @param  array<string, string>  $overrides
     */
    protected function seedSettings(array $overrides = []): void
    {
        $settings = array_merge([
            'domains' => '',
            'site_id' => '1',
            'file' => 'matomo.php',
            'script' => 'matomo.js',
            'host_analytics' => '',
        ], $overrides);

        foreach ($settings as $name => $value) {
            DB::table('settings')->updateOrInsert(
                ['group' => 'matomo', 'name' => $name],
                ['payload' => json_encode($value), 'locked' => false, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
