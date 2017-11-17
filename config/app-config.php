<?php
/**
 * This file is the loader of the `app-config`
 *
 * It loads the `app-config/base.php` file which can be referred for the set of configuration options available.
 *
 * Then this is overridden via `app-config.local.d/*.php` with the
 * order that [glob](http://php.net/manual/en/function.glob.php) returns them.
 * To ensure correct order it is best to have the files prefixed with numeric keys e.g.
 * ```
 * core/config $ ls -la app-config.local.d/*.php
 * -rw-r--r-- 1 user group 112 Feb 16 17:21 app-config.local.d/50-staging.php
 * -rw-r--r-- 1 user group 110 Feb 16 17:22 app-config.local.d/99-secrets.staging.php
 * ```
 *
 * It is expected that all the files are with valid php code that `return` array with keys
 * and things that have to be changed with the keys matching the ones in `app-config/base.php` e.g.
 * ```
 * <?php
 * return [
 *     'someconfig' => [
 *          'somenestedconfig' => false
 *     ]
 * ];
 * ```
 *
 * It is expected that the proper set of files per environment from
 * `app-config/plain/${environment}.php` and `app-config/encrypted/secrets.${environment}.php`
 * are placed in proper order within `app-config.local.d/`
 */

$appConfig = require __DIR__ . '/app-config/base.php';

$configOverridesFiles = glob(__DIR__ . '/app-config.local.d/*.php');

foreach ($configOverridesFiles as $configOverridesFile) {
    $appConfig = array_replace_recursive($appConfig, require $configOverridesFile);
}

return $appConfig;
