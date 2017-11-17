<?php
return [
    'debug' => false,
    'environment' => 'development',
    'php' => [
        'error_reporting' => E_ALL,
        'display_errors' => false
    ],
    'db.options' => [
        'host' => 'db',
        'port' => 3306,
        'user' => 'core',
        'password' => 'core',
        'dbname' => 'core',
        'charset' => 'utf8',
        'options' => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = 'UTC'"]
    ],
    'hateoas.options' => [
        'metadataDir' => __DIR__ . '/../../src/Core/Main/Infrastructure/DataTransformer/Hal',
        'cacheDir' => __DIR__ . '/../../src/Core/cache',
        'debug' => false
    ],
    'jwt' => [
        'header' => 'Authorization',
        'life_time' => 86400,
        'public_key' => file_get_contents(__DIR__ . '/../../tests/resources/public_key.pem'),
        'private_key' => file_get_contents(__DIR__ . '/../../tests/resources/private_key.pem'),
    ],
    'mailer.options' => [
        'sender_email' => 'new@gmail.com',
        'sender_name' => 'admin',
        'host' => 'smtp.gmail.com',
        'port' => '587',
        'encryption' => 'tls',
        'username' => 'new@gmail.com',
        'password' => 'CoreDevSecretPassword',
    ],
    'email.options' => [
        'baseUrl' => 'http://localhost',
        'emailVerificationPath' => '/email/verification/{hash}',
        'resetPasswordPath' => '/reset-password/{id}/password',
        'chargePath' => '/tenant/charge/{leasePaymentId}'
    ],
    'rollbar' => [
        'access_token' => 'dev-access-token-that-is-32-char'
    ]
];
