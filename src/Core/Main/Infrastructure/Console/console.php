<?php

require __DIR__ . '/../../../../../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Core\Main\Infrastructure\Console\Commands\CacheWarmup;

$application = new Application();
$application->add(new CacheWarmup());
$application->run();
