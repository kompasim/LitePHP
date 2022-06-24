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

    function writeHead($content)
    {
        assertStringExit($content);
        header($content);
    }

    function writeText($text)
    {
        assertStringExit($text);
        echo $text;
    }

    function writePhp($name, $data = [])
    {
        $path = PATH_APP . $name . ".php";
        assertFileExit($path);
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        require $path;
    }

    function writeFile($path)
    {
        assertFileExit($path);
        echo file_get_contents($path);
    }

    function writeJson($array)
    {
        assertArrayExit($array);
        echo json_encode($array);
    }

}
