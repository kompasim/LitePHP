<?php

// namespace LitePHP;

define('PATH_APP', dirname(debug_backtrace()[0]['file']) . "/");
define('PATH_LITE', __DIR__ . "/");
if (!defined('IS_DEBUG')) define('IS_DEBUG', true);

include(PATH_LITE . "other/lconstants.php");
include(PATH_LITE . "other/lsecret.php");
include(PATH_LITE . "other/lgrammar.php");
include(PATH_LITE . "other/ltool.php");
include(PATH_LITE . "lite/LApplication.php");

class Lite
{

    function __construct()
    {
        //
    }

    function __destruct()
    {
        //
    }

    function run()
    {
        // filter
        $path = trim($_SERVER['PATH_INFO'], "/");
        assertOrExit(is_string($path), "path error!");
        // parse
        $describe = trim(trim($this->parse($path), "/"), "?");
        assertOrExit(isValidString($describe), "route error!");
        // excecute
        $this->execute($describe);
    }

    function parse($path)
    {
        // router
        foreach (ROUTES_MAP as $route => $describe)
        {
            assertOrExit(preg_match("/^.+$/i", $route), "invaid route key!");
            assertOrExit(preg_match("/^(\w+)(\/\w+)$/i", $describe), "invaid route value!");
            $pattern = "/^" . str_replace('/', '\/', $route) . "(\/\w+)*$/i";
            if (preg_match($pattern, $path)) return $describe . preg_replace("/^(\w+)(\/\w+)?/i", "", $path);
        }
        // match
        if (preg_match_all("/^(\w+)\/(\w+)/i", $path, $result)) {
            if (count($result) == 3) return $path;
        }
        // invalid
        return NULL;
    }

    function execute($describe)
    {
        // check parameters
        $args = explode('/', $describe);
        assertOrExit(count($args) >= 2, 'system error!');
        $class = $args[0];
        $method = $args[1];
        define("CURRENT_APPLICATION", $class);
        define("CURRENT_FUNCTION", $method);
        $params = array_slice($args, 2, count($args) - 2);
        $file = PATH_APP . $class . '.php';
        // require file
        assertOrExit(file_exists($file), 'file not found:' . $file);
        require_once $file;
        // instantiate class
        assertOrExit(class_exists($class, false), 'class not found:' . $class);
        $object = new $class($this);
        // call method
        assertOrExit(method_exists($object, $method), 'method not found:' . $method);
        call_user_func_array(array($object, $method), $params);
    }

}
