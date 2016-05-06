<?php

/**
 * Front Controller
 */

// App bootstrap
include dirname(__DIR__) . '/boot.php';

Event::trigger('app.run');
