<?php

/**
 * Front Controller
 */

// App bootstrap
include dirname(__DIR__) . '/boot.php';

var_dump( getenv('DEBUG') );

Event::trigger('app.run');
