<?php

include_once("../Lite.php");
$lite = new Lite();

// handle the empty path
$lite->route('\s*', 'App/default');

// handle the target path
$lite->route('app/hello', 'App/hello'); // example: index.php/app/hello/Emily
$lite->route('app/hi', 'App/hi'); // example: index.php/app/hi?name=Alice

// handle with lambda function
$lite->route('app/none', function($app, $arguments) {
    $app->response->writeRedirect("app/error");
});

// handle the unmatched paths
$lite->route('.*', 'App/empty');

// handle the paths that routes cannot consume
$lite->run(function($app, $class, $method, $arguments) {
    echo "default handler ...";
});
