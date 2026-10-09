<?php

use Illuminate\Support\Facades\Vite;
use JeffersonGoncalves\Matomo\Settings\MatomoSettings;

it('stamps the CSP nonce on every script tag', function () {
    Vite::useCspNonce('test-nonce');
    $settings = app(MatomoSettings::class);
    $settings->host_analytics = 'analytics.example.com';
    $settings->site_id = '7';
    $settings->file = 'matomo.php';
    $settings->script = 'matomo.js';
    $settings->save();
    $html = (string) view('matomo::script')->render();

    preg_match_all('/<script\b[^>]*>/', $html, $tags);

    expect($tags[0])->not->toBeEmpty()->each->toContain('nonce="test-nonce"');
});

it('renders no nonce attribute when the app uses none', function () {
    $settings = app(MatomoSettings::class);
    $settings->host_analytics = 'analytics.example.com';
    $settings->site_id = '7';
    $settings->file = 'matomo.php';
    $settings->script = 'matomo.js';
    $settings->save();
    $html = (string) view('matomo::script')->render();

    expect($html)->toContain('<script')->not->toContain('nonce=');
});
