<?php

defined('PATH_LITE') or exit('denied!');

class LDatabase
{

    var $pdo = NULL;

    function __construct()
    {
        //
    }

    function __destruct()
    {
        //
    }

    function connect()
    {
        assert_or_exit(func_num_args() >= 0, 'too few arguments');
        $arguments = func_get_args();
        $dsn = $arguments[0] != NULL ? $arguments[0] : "sqlite::memory:";
        $params = array_slice($arguments, 1, count($arguments) - 1);
        $this->pdo = new PDO($dsn, ...$params);
    }

    function isOk()
    {
        assert_or_exit($this->pdo != $this, "system error!");
        return $this->pdo->errorCode() === NULL;
    }

    function getErr()
    {
        assert_or_exit($this->pdo != $this, "system error!");
        return $errorMsg = $this->pdo->errorInfo();
    }

    function __call($name, $arguments)
    {
        assert_or_exit($this->pdo != $this, "system error!");
        return call_user_func_array(array($this->pdo, $name), $arguments);
    }

}
