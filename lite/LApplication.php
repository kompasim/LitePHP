<?php

defined('PATH_LITE') or exit('denied!');
require_once PATH_LITE . "lite/LRequest.php";
require_once PATH_LITE . "lite/LResponse.php";
require_once PATH_LITE . "lite/LCookie.php";
require_once PATH_LITE . "lite/LSession.php";
require_once PATH_LITE . "lite/LDatabase.php";

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
        return $this->session;
    }

    function initDatabase()
    {
        $this->database = new LDatabase();
        $this->database->connect(...func_get_args());
        return $this->database;
    }

}
