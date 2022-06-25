<?php

defined('PATH_LITE') or exit('denied!');

class LDatabase
{

    var $medoo = NULL;

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
        assert_or_exit($this->medoo == $this, "system error!");
        $medooPath =  PATH_LITE . "third/Medoo.php";
        assert_or_exit(file_exists($medooPath), "invalid medoo!");
        require_once $medooPath;
        $this->medoo = new Medoo\Medoo($meedoInfo);
        return $this->medoo;
    }

    // 用魔术方法映射medoo

    function __set($name, $value)
    {
        assert_or_exit($this->medoo != $this, "system error!");
        $this->medoo->$name = $value;
    }

    function __get($name)
    {
        assert_or_exit($this->medoo != $this, "system error!");
        return $this->medoo->$name;
    }

    function __call($name, $arguments)
    {
        assert_or_exit($this->medoo != $this, "system error!");
        return call_user_func_array(array($this->medoo, $name), $arguments);
    }

    function isOk()
    {
        assert_or_exit($this->medoo != $this, "system error!");
        return $this->medoo->error()[2] === NULL;
    }

    function getErr()
    {
        assert_or_exit($this->medoo != $this, "system error!");
        $errorMsg = $this->medoo->error()[2];
        return $errorMsg != NULL ? $errorMsg : "";
    }

}
