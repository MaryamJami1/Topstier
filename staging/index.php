<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__ . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__ . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

// Force SCRIPT_NAME so Laravel knows we're in /staging subdirectory
// Laravel uses SCRIPT_NAME to calculate the base path and strip it from REQUEST_URI
$_SERVER['SCRIPT_NAME'] = '/staging/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/index.php';

// Debug: uncomment below to diagnose routing issues
// file_put_contents(__DIR__ . '/storage/logs/debug_request.log', 
//     date('Y-m-d H:i:s') . "\n" .
//     "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'not set') . "\n" .
//     "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'not set') . "\n" .
//     "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'not set') . "\n" .
//     "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'not set') . "\n" .
//     "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'not set') . "\n" .
//     "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'not set') . "\n" .
//     "---\n", FILE_APPEND);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
