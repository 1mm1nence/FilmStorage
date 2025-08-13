<?php
if (php_sapi_name() === 'cli-server') {
    $path = __DIR__ . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    if (is_file($path)) return false;
}

require_once __DIR__ . '/../src/Core/autoload.php';
require_once __DIR__ . '/../config/config.php';
$boostraped = require __DIR__ . '/../bootstrap.php';

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

use App\Core\Router;

$router = new Router($boostraped['pdo']);
$router->handle($_SERVER['REQUEST_URI']);