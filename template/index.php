<?php

// routes
define('ROUTE_DEFAULT', 'App/default'); // default
define('ROUTE_EMPTY', 'App/empty'); // 404
define('ROUTES_MAP', [
    'app/hello' => 'app/hello', // example: index.php/app/hello/Emily
    'app/hi' => 'app/hi', // example: index.php/app/hi?name=Alice
    'someController/someMethod' => 'app/hi',
]);

include_once("../Lite.php");
$lite = new Lite();
$lite->run();
