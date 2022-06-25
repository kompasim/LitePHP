<?php

defined('PATH_LITE') or exit('denied!');

class LResponse
{

    function __construct()
    {
       //
    }

    function __destruct()
    {
        //
    }

    function writeRedirect($path)
    {
        assert_valid_string($path);
        $url = $_SERVER['SCRIPT_NAME'] . "/" . $path;
        echo "<script>window.location.href='" . $url . "';</script>";
        exit;
    }

    function writeHead($content)
    {
        assert_valid_string($content);
        header($content);
    }

    function writeText($text)
    {
        assert_valid_string($text);
        echo $text;
    }

    function writePhp($name, $data = [])
    {
        $path = PATH_APP . $name . ".php";
        assert_valid_file($path);
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        require $path;
    }

    function writeFile($path)
    {
        assert_valid_file($path);
        echo file_get_contents($path);
    }

    function writeJson($array)
    {
        assert_valid_array($array);
        echo json_encode($array);
    }

}
