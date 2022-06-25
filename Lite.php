<?php

// namespace LitePHP;

define('PATH_APP', dirname(debug_backtrace()[0]['file']) . "/");
define('PATH_LITE', __DIR__ . "/");
if (!defined('IS_DEBUG')) define('IS_DEBUG', true);

include(PATH_LITE . "other/lsecret.php");
include(PATH_LITE . "other/lgrammar.php");
include(PATH_LITE . "other/ltool.php");
include(PATH_LITE . "lite/LApplication.php");

class Lite
{

    function __construct()
    {
        $this->routes = [];
        $this->handler = function() {};
    }

    function __destruct()
    {
        //
    }

    function route($rule, $argument)
    {
        assertOrExit(preg_match("/^.+$/i", $rule), "invaid route rule!");
        if (is_string($argument)) {
            assertOrExit(preg_match("/^(\w+)(\/\w+)$/i", $argument), "invaid route describe!");
        } else {
            assertOrExit(is_callable($argument), "invalid route argument!");
        }
        $route = array($rule, $argument);
        array_push($this->routes, $route);
    }

    function run($handler)
    {
        if(is_callable($handler)) $this->handler = $handler;
        // filter
        $path = trim(trim($_SERVER['PATH_INFO'], "/"), "?");
        assertOrExit(is_string($path), "path error!");
        // parse
        $func = NULL;
        $desc = NULL;
        $route = $this->parse($path);
        if ($route != NULL && is_callable($route[1])) $func = $route[1];
        if ($route != NULL && is_string($route[1])) $desc = $route[1];
        // run
        if ($func != NULL) {
            // execute
            $box = $this->explode($path);
            $this->execute($func, $box);
        } else if ($desc != NULL) {
            // dispatch
            $box = $this->explode($desc);
            $this->dispatch($box);
        } else {
            // handle
            $box = $this->explode($path);
            $this->handle($class, $method, $params);
        }
    }

    private function explode($desc)
    {
        $args = explode('/', $desc);
        assertOrExit(count($args) >= 2, 'system error!');
        $class = $args[0];
        $method = $args[1];
        define("CURRENT_APPLICATION", $class);
        define("CURRENT_FUNCTION", $method);
        $params = array_slice($args, 2, count($args) - 2);
        return array($class, $method, $params);
    }

    private function parse($path)
    {
        $route = NULL;
        foreach ($this->routes as $index => $value)
        {
            $rule = $value[0];
            $argument = $value[1];
            $pattern = "/^" . str_replace('/', '\/', $rule) . "(\/\w+)*$/i";
            if (preg_match($pattern, $path)) $route = $value;
            if ($route != NULL && is_string($argument)) $route[1] = $argument . preg_replace("/^(\w+)(\/\w+)?/i", "", $path);
            if ($route != NULL) break;
        }
        return $route;
    }

    private function execute($func, $box)
    {
        $class = $box[0];
        $method = $box[1];
        $params  = $box[2];
        $app = new LApplication($this);
        call_user_func_array($func, array($app, $params));
        return $app;
    }

    private function dispatch($box)
    {
        $class = $box[0];
        $method = $box[1];
        $params  = $box[2];
        $file = PATH_APP . $class . '.php';
        assertOrExit(file_exists($file), 'file not found:' . $file);
        require_once $file;
        assertOrExit(class_exists($class, false), 'class not found:' . $class);
        $object = new $class($this);
        assertOrExit(method_exists($object, $method), 'method not found:' . $method);
        call_user_func_array(array($object, $method), $params);
        return $object;
    }

    private function handle($box)
    {
        $class = $box[0];
        $method = $box[1];
        $params  = $box[2];
        $app = new LApplication($this);
        call_user_func_array($this->handler, array($app, $class, $method, $params));
        return $app;
    }

}
