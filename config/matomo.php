<?php

return [
    'file' => env('MATOMO_FILE_PHP', 'piwik.php'),
    'script' => env('MATOMO_FILE_JS', 'piwik.js'),
    'host_analytics' => env('MATOMO_HOST_ANALYTICS', '//localhost/matomo'),
];
