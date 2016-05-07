<?php

/**
 * Application Bootstrap
 */

define ('APP_DIR', __DIR__);
require APP_DIR.'/vendor/autoload.php';

try {
    (new josegonzalez\Dotenv\Loader(APP_DIR))->parse()->putenv();
} catch (Exception $e) {
    //
}

$_response_send = new Deferred(function() {
    Response::sent() || Response::send();
});

// Caching strategy
Cache::using([
  'files' => [
    'cache_dir' => getenv('CACHE_DIR') || sys_get_temp_dir()
  ],
]);

// Init Views
View::using(new View\Twig(APP_DIR.'/templates',[
    'cache'         => getenv('CACHE_VIEWS') ? getenv('CACHE_DIR') : false,
    'auto_reload'   => getenv('DEBUG'),
]));

View::addGlobals([
  'BASE_URL'  => rtrim(dirname($_SERVER['PHP_SELF']),'/').'/',
  'CACHEBUST' => getenv('DEBUG') ? '?v='.time() : '',
]);

// Routes
foreach (glob(APP_DIR.'/routes/*.php') as $routedef) include $routedef;
Event::trigger('app.dispatch');
Route::dispatch();
