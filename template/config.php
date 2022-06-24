<?php

// debug mode
define('IS_DEBUG', true);

// related paths
define('PATH_APP', __DIR__ . "/");
define('PATH_LITE', PATH_APP . "../");
define('PATH_CONTROLLER',PATH_APP);

// string md5 salt
define('SECRET_STRING', "");

// routes
define('ROUTE_DEFAULT', 'App/default'); // default
define('ROUTE_EMPTY', 'App/empty'); // 404
define('ROUTES_MAP', [
    'app/hello' => 'app/hello', // example: index.php/app/hello/Emily
    'app/hi' => 'app/hi', // example: index.php/app/hi?name=Alice
    'someController/someMethod' => 'app/hi',
]);

// database info (medoo)
define('DATABASE_INFO', [
    'database_type' => 'sqlite',
    'database_file' => ':memory:'
]);
