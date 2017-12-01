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
        'cacheDir' => __DIR__ . '/../../var/cache',
        'debug' => false
    ],
    'jwt' => [
        'header' => 'Authorization',
        'life_time' => 86400,
        'public_key' => file_get_contents(__DIR__ . '/../../tests/resources/public_key.pem'),
        'private_key' => file_get_contents(__DIR__ . '/../../tests/resources/private_key.pem'),
    ],
    'google' => [
        'google_project_id' => 'doctor-degree',
        'google_key_file_path' => __DIR__ . '/../../Doctor-9a5a32547500.json'
    ],
    'mailer.options' => [
        'sender_email' => 'a@b.c',
        'sender_name' => 'a@b.c',
        'host' => 'smtp.gmail.com',
        'port' => '587',
        'encryption' => 'tls',
        'username' => 'a@b.c',
        'password' => 'a@b.c',
    ],
    'email.options' => [
        'baseUrl' => 'http://localhost',
        'emailVerificationPath' => '/email/verification/{hash}',
        'resetPasswordPath' => '/reset-password/{id}/password'
    ],
    'rollbar' => [
        'access_token' => 'dev-access-token-that-is-32-char'
    ],
    'em.options' => [
        'debug' => false,
        'echosql' => false
    ],
];
