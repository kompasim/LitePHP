<?php

defined('PATH_LITE') or exit('denied!');
require_once PATH_LITE . "lite/LRequest.php";
require_once PATH_LITE . "lite/LResponse.php";
require_once PATH_LITE . "lite/LCookie.php";
require_once PATH_LITE . "lite/LSession.php";

class LApplication
{

    final function __construct($lite)
    {
        assert_or_throw($this->lite === NULL, 'invalid call');
        $this->lite = $lite;
        $this->request = new LRequest();
        $this->response = new LResponse();
        $this->cookie = new LCookie();
        $this->session = new LSession();
        $this->cookie->start();
        $this->session->start();
        $this->onCreate();
    }

    final function __destruct()
    {
        assert_or_throw($this->lite !== NULL, 'invalid call');
        $this->lite = NULL;
        $this->onDestroy();
        $this->cookie->destroy();
        $this->session->destroy();
    }

    function onCreate() {
        // overwrite this
    }

    function onDestroy() {
        // overwrite this
    }

}
