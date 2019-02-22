<?php



function app()
{
    $swoole = [

        'servers' => [

            'laravel_http' => [
                'driver' => \CrCms\Microservice\Server\Http\Server::class,
                'host' => '0.0.0.0',
                'port' => 28082,
                'settings' => [
                    'user' => env('SWOOLE_USER'),
                    'group' => env('SWOOLE_GROUP'),
                    'log_level' => 4,
                    'log_file' => '/var/log/laravel_http.log',
                ],
            ],
        ],

        'laravel' => [


//        'app' => \CrCms\Server\Drivers\Laravel\Application::class,


            'preload' => [
            ],

            'providers' => [

            ],

            /*
            |--------------------------------------------------------------------------
            | Laravel resetters
            |--------------------------------------------------------------------------
            |
            | Every time you need to load an object that needs to be reset
            | Please note the order of execution of the load
            |
            */

            'resetters' => [
                \CrCms\Server\Drivers\Laravel\Resetters\ConfigResetter::class,
                \CrCms\Server\Drivers\Laravel\Resetters\ProviderResetter::class,
            ],

            /*
            |--------------------------------------------------------------------------
            | Laravel events
            |--------------------------------------------------------------------------
            |
            | Available events
            | start: onStart
            | worker_start: onWorkerStart
            | request: onRequest
            |
            */

            'events' => [
            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | ProcessManager file
        |--------------------------------------------------------------------------
        |
        | Information file for saving all running processes
        |
        */

        'process_file' => '/var/process.pid',

        /*
        |--------------------------------------------------------------------------
        | Swoole Process Prefix
        |--------------------------------------------------------------------------
        |
        | Server process name prefix
        |
        */

        'process_prefix' => 'swoole',
    ];


    $app = \Illuminate\Container\Container::getInstance();



    $app->instance('config',new \Illuminate\Config\Repository(['swoole' => $swoole]));

    return $app;
}


$testTempPath = './tmp';

if (!file_exists($testTempPath)) {
    mkdir($testTempPath, 0777, true);
}