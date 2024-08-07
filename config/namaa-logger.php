<?php

use Namaa\NamaaLogger\Watchers;

return [

    'queue_name' => env('N_LOGGER_QUEUE_NAME', 'log'),

    'elastic_cloud_id' => env('N_LOGGER_ELASTIC_CLOUD_ID', ''),

    'elastic_host' => env('N_LOGGER_ELASTIC_HOST', ''),

    'elastic_api_key' => env('N_LOGGER_ELASTIC_API_KEY', ''),

    'elastic_index' => env('N_LOGGER_ELASTIC_INDEX', ''),

    'elastic_lifecycle_policy' => env('N_LOGGER_ELASTIC_LIFECYCLE_POLICY', null),

    'elastic_username' => env('N_LOGGER_ELASTIC_USERNAME', ''),

    'elastic_password' => env('N_LOGGER_ELASTIC_PASSWORD', ''),

    'storage' => [
        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'chunk' => 1000,
        ],
    ],


    'enabled' => env('N_LOGGER_ENABLED', false),


    /*
    |--------------------------------------------------------------------------
    | Allowed / Ignored Paths & Commands
    |--------------------------------------------------------------------------
    |
    | The following array lists the URI paths and Artisan commands that will
    | not be watched by Namaa Logger. In addition to this list, some Laravel
    | commands, like migrations and queue commands, are always ignored.
    |
    */

    'only_paths' => [
        // 'api/*'
    ],

    'ignore_paths' => [
        'livewire*',
        'nova-api*',
        'pulse*',
    ],

    'ignore_commands' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Namaa Logger Watchers
    |--------------------------------------------------------------------------
    |
    | The following array lists the "watchers" that will be registered with
    | Namaa Logger. The watchers gather the application's profile data when
    | a request or task is executed. Feel free to customize this list.
    |
    */

    'watchers' => [
            // Watchers\JobWatcher::class => env('N_LOGGER_JOB_WATCHER', true),

        Watchers\LogWatcher::class => [
            'enabled' => env('N_LOGGER_LOG_WATCHER', true),
            'level' => 'warning',
        ],

            // Watchers\QueryWatcher::class => [
            //     'enabled' => env('N_LOGGER_QUERY_WATCHER', true),
            //     'ignore_packages' => true,
            //     'ignore_paths' => [],
            //     'slow' => 100,
            // ],

        Watchers\RequestWatcher::class => [
            'enabled' => env('N_LOGGER_REQUEST_WATCHER', true),
            'size_limit' => env('N_LOGGER_RESPONSE_SIZE_LIMIT', 64),
            'ignore_http_methods' => [],
            'ignore_status_codes' => [],
        ],
    ],
];
