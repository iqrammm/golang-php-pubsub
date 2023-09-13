<?php

use Interop\Amqp\AmqpTopic;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Laravel's queue API supports an assortment of back-ends via a single
    | API, giving you convenient access to each back-end using the same
    | syntax for every one. Here you may define a default connection.
    |
    */

    'default' => env('QUEUE_CONNECTION', 'rabbitmq'),

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection information for each server that
    | is used by your application. A default configuration has been added
    | for each back-end shipped with Laravel. You are free to add more.
    |
    | Drivers: "sync", "database", "beanstalkd", "sqs", "redis", "null"
    |
    */

    'connections' => [
        // 'rabbitmq' => [
        //     'driver' => 'rabbitmq',
        //     'worker' => env('RABBITMQ_WORKER', 'default'),
        //     'dsn' => env('RABBITMQ_DSN', null),
        //     'factory_class' => \Enqueue\AmqpBunny\AmqpConnectionFactory::class,
        //     'host' => env('RABBITMQ_HOST', '127.0.0.1'),
        //     'port' => env('RABBITMQ_PORT', 5672),
        //     'vhost' => env('RABBITMQ_VHOST', '/'),
        //     'user' => env('RABBITMQ_USER', 'rabbitmq'),
        //     'password' => env('RABBITMQ_PASSWORD', 'rabbitmq'),
        //     'queue' => env('RABBITMQ_QUEUE', 'disk_space'),
        //     'options' => [
        //         'exchange' => [
        //         'name' => env('RABBITMQ_EXCHANGE_NAME'),
        //         'type' => env('RABBITMQ_EXCHANGE_TYPE',AmqpTopic::TYPE_DIRECT),
        //         'passive' => env('RABBITMQ_EXCHANGE_PASSIVE', false),
        //         'durable' => env('RABBITMQ_EXCHANGE_DURABLE', true),
        //         'auto_delete' => env('RABBITMQ_EXCHANGE_AUTODELETE', false),
        //         'arguments' => env('RABBITMQ_EXCHANGE_ARGUMENTS'),
        //         'job' => \App\Jobs\RabbitMQJob::class,
        //     ],
        //     'queue' => [
        //         'declare' => env('RABBITMQ_QUEUE_DECLARE', true),
        //         'bind' => env('RABBITMQ_QUEUE_DECLARE_BIND', true),
        //         'passive' => env('RABBITMQ_QUEUE_PASSIVE', false),
        //         'durable' => env('RABBITMQ_QUEUE_DURABLE', true),
        //         'exclusive' => env('RABBITMQ_QUEUE_EXCLUSIVE', false),
        //         'auto_delete' => env('RABBITMQ_QUEUE_AUTODELETE', false),
        //         'arguments' => env('RABBITMQ_QUEUE_ARGUMENTS'),
        //         ],
        //     ],
        //     'ssl_options' => [
        //         'ssl_on' => env('RABBITMQ_SSL', false),
        //         'cafile' => env('RABBITMQ_SSL_CAFILE', null),
        //         'local_cert' => env('RABBITMQ_SSL_LOCALCERT', null),
        //         'local_key' => env('RABBITMQ_SSL_LOCALKEY', null),
        //         'verify_peer' => env('RABBITMQ_SSL_VERIFY_PEER', true),
        //         'passphrase' => env('RABBITMQ_SSL_PASSPHRASE', null),
        //     ],
        // ],
        'rabbitmq' => [
            'driver' => 'rabbitmq',
            'queue' => env('RABBITMQ_QUEUE', 'rabbitmq'),
            'connection' => PhpAmqpLib\Connection\AMQPLazyConnection::class,
            
            'hosts' => [
                [
                    'host' => env('RABBITMQ_HOST', '127.0.0.1'),
                    'port' => env('RABBITMQ_PORT', 5672),
                    'user' => env('RABBITMQ_USER', 'guest'),
                    'password' => env('RABBITMQ_PASSWORD', 'guest'),
                    'vhost' => env('RABBITMQ_VHOST', '/'),
                ],
            ],
            
            'options' => [
                'ssl_options' => [
                    'cafile' => env('RABBITMQ_SSL_CAFILE', null),
                    'local_cert' => env('RABBITMQ_SSL_LOCALCERT', null),
                    'local_key' => env('RABBITMQ_SSL_LOCALKEY', null),
                    'verify_peer' => env('RABBITMQ_SSL_VERIFY_PEER', true),
                    'passphrase' => env('RABBITMQ_SSL_PASSPHRASE', null),
                ],
                'queue' => [
                    'job' => \App\Jobs\RabbitMQJob::class,
                ],
            ],
            
            'worker' => env('RABBITMQ_WORKER', 'default'),
            'after_commit' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Job Batching
    |--------------------------------------------------------------------------
    |
    | The following options configure the database and table that store job
    | batching information. These options can be updated to any database
    | connection and table which has been defined by your application.
    |
    */

    'batching' => [
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'job_batches',
    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control which database and table are used to store the jobs that
    | have failed. You may change them to any database / table you wish.
    |
    */

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],

];
