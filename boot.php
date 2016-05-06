<?php

/**
 * Application Bootstrap
 */

define ('APP_DIR', __DIR__);
require APP_DIR.'/vendor/autoload.php';

try {
    (new Dotenv\Dotenv(APP_DIR))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {

}

$_response_send = new Deferred(function() {
    Response::sent() || Response::send();
});

// Load options
Options::loadPHP(APP_DIR.'/config.php');

// Temp directory
define ('TEMP_DIR', Options::get('cache.directory', sys_get_temp_dir()));

// Caching strategy
Cache::using([
  'files' => [
    'cache_dir' => TEMP_DIR
  ],
]);

// Init Views
View::using(new View\Twig(APP_DIR.'/templates',[
    'cache'         => Options::get('cache.views',true) ? TEMP_DIR : false,
    'auto_reload'   => Options::get('debug',false),
]));

View::addGlobals([
  'BASE_URL'  => rtrim(dirname($_SERVER['PHP_SELF']),'/').'/',
  'CACHEBUST' => Options::get('debug',false) ? '?v='.time() : '',
]);

// Routes
foreach (glob(APP_DIR.'/routes/*.php') as $routedef) include $routedef;
Event::trigger('app.dispatch');
Route::dispatch();
