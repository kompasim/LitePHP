<?php

// namespace LitePHP;

define('PATH_APP', dirname(debug_backtrace()[0]['file']) . "/");
define('PATH_LITE', __DIR__ . "/");
if (!defined('IS_DEBUG')) define('IS_DEBUG', true);

include(PATH_LITE . "other/lsecret.php");
include(PATH_LITE . "other/ltools.php");
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
        assert_or_exit(preg_match("/^.+$/i", $rule), "invaid route rule!");
        if (is_string($argument)) {
            assert_or_exit(preg_match("/^(\w+)(\/\w+)$/i", $argument), "invaid route describe!");
        } else {
            assert_or_exit(is_callable($argument), "invalid route argument!");
        }
        $route = array($rule, $argument);
        array_push($this->routes, $route);
        return $this;
    }

    function run($handler = NULL)
    {
        if(is_callable($handler)) $this->handler = $handler;
        // filter
        $path = trim(trim($_SERVER['PATH_INFO'], "/"), "?");
        assert_or_exit(is_string($path), "path error!");
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
            return $this->execute($func, $box);
        } else if ($desc != NULL) {
            // dispatch
            $box = $this->explode($desc);
            return $this->dispatch($box);
        } else {
            // handle
            $box = $this->explode($path);
            return $this->handle($box);
        }
    }

    private function explode($desc)
    {
        $args = explode('/', $desc);
        $class = is_valid_string($args[0]) ? $args[0] : NULL;
        $method = is_valid_string($args[1]) ? $args[1] : NULL;
        $params = array_slice($args, 2, count($args) - 2);
        define("CURRENT_APPLICATION", $class);
        define("CURRENT_FUNCTION", $method);
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
        assert_or_exit(file_exists($file), 'file not found:' . $file);
        require_once $file;
        assert_or_exit(class_exists($class, false), 'class not found:' . $class);
        $object = new $class($this);
        assert_or_exit(method_exists($object, $method), 'method not found:' . $method);
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
