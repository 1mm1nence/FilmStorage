<?php
if (php_sapi_name() === 'cli-server') {
    $path = __DIR__ . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    if (is_file($path)) return false;
}

require_once __DIR__ . '/../src/Core/autoload.php';
require_once __DIR__ . '/../config/config.php';

session_start();

use App\Core\Router;

$router = new Router();
$router->handle($_SERVER['REQUEST_URI']);