<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Helpers.php';
require_once __DIR__ . '/app/core/Auth.php';
require_once __DIR__ . '/app/core/Model.php';
require_once __DIR__ . '/app/core/Controller.php';

spl_autoload_register(function(string $class): void {
    $paths = [__DIR__.'/app/controllers/'.$class.'.php', __DIR__.'/app/models/'.$class.'.php', __DIR__.'/app/core/'.$class.'.php'];
    foreach ($paths as $p) { if (is_file($p)) { require_once $p; return; } }
});

(new Router())->dispatch();
