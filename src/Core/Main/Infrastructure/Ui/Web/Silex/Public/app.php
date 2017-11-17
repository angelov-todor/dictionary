<?php

// Dummy comment

$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI'] ?? '');
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

require_once __DIR__ . '/../../../../../../../../vendor/autoload.php';

$app = Core\Main\Infrastructure\Ui\Web\Silex\Application::bootstrap();
$app->run();
