<?php

use JeffersonGoncalves\Matomo\Settings\MatomoSettings;

it('can resolve MatomoSettings from the container', function () {
    expect(app(MatomoSettings::class))->toBeInstanceOf(MatomoSettings::class);
});

it('has empty host_analytics and domains by default', function () {
    $settings = app(MatomoSettings::class);

    expect($settings->host_analytics)->toBe('')
        ->and($settings->domains)->toBe('');
});

it('can persist settings', function () {
    $settings = app(MatomoSettings::class);
    $settings->site_id = '99';
    $settings->save();

    expect(app(MatomoSettings::class)->site_id)->toBe('99');
});

it('renders the tracking script when host_analytics is set', function () {
    $settings = app(MatomoSettings::class);
    $settings->host_analytics = 'analytics.example.com';
    $settings->site_id = '7';
    $settings->file = 'matomo.php';
    $settings->script = 'matomo.js';
    $settings->save();

    $view = view('matomo::script')->render();

    expect($view)
        ->toContain('analytics.example.com')
        ->toContain("setSiteId', '7'")
        ->toContain('matomo.php')
        ->toContain('matomo.js')
        ->toContain('trackPageView');
});

it('does not render the script when host_analytics is empty', function () {
    // host_analytics defaults to '' from the seeded settings.
    $view = view('matomo::script')->render();

    expect(trim($view))->toBe('')
        ->and($view)->not->toContain('_paq');
});

it('gates rendering on host_analytics, not domains', function () {
    // Regression: previously the gate keyed off the unused "domains" field, so
    // a configured domain would emit a broken script with no analytics host.
    $settings = app(MatomoSettings::class);
    $settings->domains = 'example.com';
    $settings->host_analytics = '';
    $settings->save();

    $view = view('matomo::script')->render();

    expect($view)->not->toContain('_paq');
});

it('emits setDomains when domains are configured', function () {
    $settings = app(MatomoSettings::class);
    $settings->host_analytics = 'analytics.example.com';
    $settings->domains = 'example.com, www.example.com';
    $settings->save();

    $view = view('matomo::script')->render();

    expect($view)
        ->toContain('setDomains')
        ->toContain('example.com')
        ->toContain('www.example.com');
});

it('does not emit setDomains when domains are empty', function () {
    $settings = app(MatomoSettings::class);
    $settings->host_analytics = 'analytics.example.com';
    $settings->domains = '';
    $settings->save();

    $view = view('matomo::script')->render();

    expect($view)
        ->toContain('_paq')
        ->not->toContain('setDomains');
});
