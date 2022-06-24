<?php

// routes

$route = [
    '\s*' => 'App/default', // default
    'app/hello' => 'App/hello', // example: index.php/app/hello/Emily
    'app/hiii' => 'App/hi', // example: index.php/app/hi?name=Alice
    '.*' => 'App/empty', // 404
];

include_once("../Lite.php");
$lite = new Lite();
$lite->run($route);

// 
/**
 * run with defined route rule, choose the class and method name according to the rule and call the mathing method of the class
 * example: http://test.xyz/test/index.php/class/method/argument1/argument2
 * @param route
 * @return application
 */
// $lite->run($route);

/**
 * run with default directory structure, find class in this directory and call the matching method
 * example: http://test.xyz/test/index.php/class/method/argument1/argument2
 * @param NULL
 * @return application
 */
// $lite->run(NULL);

/**
 * run with a powerfull handler function, parse url and create LApplication then call the callback
 * example: http://test.xyz/test/index.php/class/method/argument1/argument2
 * @param function (app, class, method, arguments)
 * @return application
 */
// $lite->run(function($app, $class, $method, $arguments) {});
