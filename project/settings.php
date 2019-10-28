<?php
define('APP_ROOT', __DIR__);

return [
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => false,

        'doctrine' => [
            // if true, metadata caching is forcefully disabled
            'dev_mode' => true,

            // path where the compiled metadata info will be cached
            // make sure the path exists and it is writable
            'cache_dir' => '/var/tmp',

            // you should add any other path containing annotated entity classes
            'metadata_dirs' => [APP_ROOT . '/src/Entities'],

            'connection' => [
                'driver' => 'pdo_sqlite',
                'path' => APP_ROOT . '/src/Database/db.sqlite',
                /*
                'driver' => 'pdo_mysql',
                'host' => 'localhost',
                'port' => 3306,
                'dbname' => 'mydb',
                'user' => 'user',
                'password' => 'secret',
                */
                'charset' => 'utf-8'
            ]
        ],
        'privateKey' => 'file://' . APP_ROOT . '/private.key',
        'publicKey' => 'file://' . APP_ROOT . '/public.key',
        'encryptionKey' => 'lxZFUEsBCJ2Yb14IF2ygAHI5N4+ZAUXXaSeeJm6+twsUmIen',
    ]
];