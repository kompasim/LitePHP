<?php

defined('PATH_LITE') or exit('denied!');
require_once PATH_LITE . "lite/LRequest.php";
require_once PATH_LITE . "lite/LResponse.php";
require_once PATH_LITE . "lite/LCookie.php";
require_once PATH_LITE . "lite/LSession.php";

class LApplication
{

    function __construct()
    {
        $this->request = new LRequest();
        $this->response = new LResponse();
    }

    function __destruct()
    {
        //
    }

    function initCookie()
    {
        $this->cookie = new LCookie();
        $this->cookie->start();
        return $this->cookie;
    }

    function initSession()
    {
        $this->session = new LSession();
        $this->session->start();
        $this->session;
    }

    function loadThrid()
    {
        try {
            assert_or_exit(func_num_args() >= 2);
            $arguments = func_get_args();
            $path = $arguments[0];
            $class = $arguments[1];
            $params = array_slice($arguments, 2, count($arguments) - 2);
			require_once PATH_LITE . "third/" . $path;
			return new $class(...$params);
		} catch(Exception $err) {
			return NULL;
		} 
    }

}
