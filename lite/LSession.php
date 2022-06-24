<?php

defined('PATH_LITE') or exit('denied!');

class LSession
{

    function __construct()
    {
        //
    }

    function __destruct()
    {
        //
    }

    function start()
    {
        session_start();
    }

    
    function get($key, $default)
    {
        return $this->has($key) && $this->type($key) == gettype($default) ? $_SESSION[$key] : $default;
    }

    function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    function has($key)
    {
        return isset($_SESSION[$key]);
    }

    function type($key)
    {
        return $this->has($key) ? gettype($_SESSION[$key]) : null;
    }

    function delete($key)
    {
        unset($_SESSION[$key]);
    }

    function destroy()
    {
        session_destroy();
    }

}
