<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('matomo.domains', '');
        $this->migrator->add('matomo.site_id', '1');
        $this->migrator->add('matomo.file', 'matomo.php');
        $this->migrator->add('matomo.script', 'matomo.js');
        $this->migrator->add('matomo.host_analytics', '');
    }
};
