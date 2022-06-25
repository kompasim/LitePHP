<?php

defined('PATH_LITE') or exit('denied!');

class LCookie
{

    function start()
    {
        //
    }
    
    function get($key, $default)
    {
        return $this->has($key) && $this->type($key) == gettype($default) ? $_COOKIE[$key] : $default;
    }

    function set($key, $value, $expire)
    {
        setcookie($key, $value, time() + $expire);
    }

    function has($key)
    {
        return isset($_COOKIE[$key]);
    }

    function type($key)
    {
        return $this->has($key) ? gettype($_COOKIE[$key]) : NULL;
    }

    function delete($key)
    {
        setcookie($key, "", time() - 3600);
    }

    function destroy()
    {
        //
    }

}
