<?php
if(file_exists(__DIR__.'/.env')) {
    $dotenv = Dotenv\Dotenv::create(__DIR__.'/');
    $dotenv->overload();
}

return [
    'paths' => [
        'migrations' => getenv('PHINX_CONFIG_DIR'),
        'seeds' => 'db/seed'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => getenv('PHINX_DB_HOST'),
            'name' => getenv('PHINX_DB_NAME'),
            'user' => getenv('PHINX_DB_USER'),
            'pass' => getenv('PHINX_DB_PASSWD'),
            'port' => 3306,
            'charset' => 'utf8'
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => getenv('DB_HOST'),
            'name' => getenv('DB_DATABASE'),
            'user' => getenv('DB_USERNAME'),
            'pass' => getenv('DB_PASSWORD'),
            'port' => 3306,
            'charset' => 'utf8'
        ]
    ],
    'version_order' => 'creation'
];
