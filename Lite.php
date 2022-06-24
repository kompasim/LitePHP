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
        //
        $this->config = null;
        // filter
        $info = explode("&", $_SERVER['QUERY_STRING'])[0];
        $info = trim(trim($info, "/"), "?");
        // parse
        $describe = $this->parse($info);
        $describe = trim(trim($describe, "/"), "?");
        // check
        assertOrExit(isValidString($describe), "route error!");
        // excecute
        $this->execute($describe);
    }

    // execute controller
    function execute($describe)
    {
        // check parameters
        $args = explode('/', $describe);
        count($args) >= 2 or exit("system error!");
        $class = $args[0];
        $method = $args[1];
        define("CURRENT_APPLICATION", $class);
        define("CURRENT_FUNCTION", $method);
        $params = array_slice($args, 2, count($args) - 2);
        // check file
        $file = PATH_APP . $class . '.php';
        if (!file_exists($file)) {
            $this->redirect(ROUTE_EMPTY);
        }
        require_once $file;
        // check class
        if (!class_exists($class, false)) {
            $this->redirect(ROUTE_EMPTY);
        }
        $object = new $class($this);
        // check method
        if (!method_exists($object, $method)) {
            assertOrExit($describe != ROUTE_EMPTY, "empty route not set");
            $this->redirect(ROUTE_EMPTY);
        }
        // call
        call_user_func_array(array($object, $method), $params);
    }

    // parser route
    function parse($path)
    {
        // default
        if (preg_match("/^\s*$/", $path)) return ROUTE_DEFAULT;
        // router
        foreach (ROUTES_MAP as $route => $describe)
        {
            $patternMatch = "/^" . str_replace('/', '\/', $route) . "(\/[a-zA-Z0-9]+)*$/i";
            assertOrExit(preg_match(PATTERN_ROUTEE_CHECK, $describe), "invaid route config value!");
            assertOrExit(preg_match(PATTERN_ROUTEE_CHECK, $route), "invaid route config key!");
            if (preg_match($patternMatch, $path)) {
                return $describe . preg_replace(PATTERN_ROUTEE_REPLACE, "", $path);
            }
        }
        // match
        if (preg_match_all(PATTERN_ROUTEE_REPLACE, $path, $result)) {
            if (count($result) == 3) return $path;
        }
        // empty
        return ROUTE_EMPTY;
    }

    function redirect($describe)
    {
        redirectAndExit($_SERVER['SCRIPT_NAME'] . "/" . $describe);
    }

}
