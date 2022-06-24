<?php

defined('PATH_LITE') or exit('denied!');

class LDatabase
{

    var $medoo = null;

    function __construct()
    {
        $this->medoo = $this;
    }

    function __destruct()
    {
        //
    }

    function connect($meedoInfo)
    {
        assertOrExit($this->medoo == $this, "system error!");
        $medooPath =  PATH_THIRD . "Medoo.php";
        assertOrExit(file_exists($medooPath), "invalid medoo!");
        require_once $medooPath;
        $this->medoo = new Medoo\Medoo($meedoInfo);
        return $this->medoo;
    }

    // 用魔术方法映射medoo

    function __set($name, $value)
    {
        assertOrExit($this->medoo != $this, "system error!");
        $this->medoo->$name = $value;
    }

    function __get($name)
    {
        assertOrExit($this->medoo != $this, "system error!");
        return $this->medoo->$name;
    }

    function __call($name, $arguments)
    {
        assertOrExit($this->medoo != $this, "system error!");
        return call_user_func_array(array($this->medoo, $name), $arguments);
    }

    function isOk()
    {
        assertOrExit($this->medoo != $this, "system error!");
        return $this->medoo->error()[2] === NULL;
    }

    function getErr()
    {
        assertOrExit($this->medoo != $this, "system error!");
        $errorMsg = $this->medoo->error()[2];
        return $errorMsg != NULL ? $errorMsg : "";
    }

}
