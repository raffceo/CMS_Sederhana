<?php

return [
    'db' => [
        'dsn' => "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}",
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASS'],
    ],
    'app' => [
        'name' => $_ENV['APP_NAME'] ?? 'CMS Sederhana',
        'url' => $_ENV['APP_URL'] ?? 'http://localhost',
        'debug' => $_ENV['APP_DEBUG'] ?? false,
    ],
    'mail' => [
        'host' => $_ENV['MAIL_HOST'],
        'port' => $_ENV['MAIL_PORT'],
        'username' => $_ENV['MAIL_USERNAME'],
        'password' => $_ENV['MAIL_PASSWORD'],
        'encryption' => $_ENV['MAIL_ENCRYPTION'],
        'from' => [
            'address' => $_ENV['MAIL_FROM_ADDRESS'],
            'name' => $_ENV['MAIL_FROM_NAME'],
        ],
    ],
]; 