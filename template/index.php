<?php

// routes

define('ROUTES_MAP', [
    '\s*' => 'App/default', // default
    'app/hello' => 'App/hello', // example: index.php/app/hello/Emily
    'app/hi' => 'App/hi', // example: index.php/app/hi?name=Alice
    '.*' => 'App/empty', // 404
]);

include_once("../Lite.php");
$lite = new Lite();
$lite->run();
