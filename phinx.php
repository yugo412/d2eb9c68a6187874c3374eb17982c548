<?php

include 'vendor/autoload.php';

$env = parse_ini_file(realpath('.env'));

foreach ($env as $key => $value) {
    set_env($key, $value);
}

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => get_env('DB_DRIVER'),
            'host' => get_env('DB_HOST'),
            'name' => get_env('DB_NAME'),
            'user' => get_env('DB_USERNAME'),
            'pass' => get_env('DB_PASSWORD'),
            'port' => get_env('DB_PORT'),
            'charset' => get_env('DB_CHARSET'),
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'testing_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
