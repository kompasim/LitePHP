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

    function writeRedirect($url, $code = 303)
    {
        assert_valid_string($url);
        if(!preg_match("/^https?:\/\/\w+\.\w+.*$/i", $url)) $url = $_SERVER['SCRIPT_NAME'] . "/" . $url;
        header('Location:' . $url, true, $code);
    }

    function writeHead($content)
    {
        assert_valid_string($content);
        header($content);
    }

    function writeCode($code = 200)
    {
        assert_valid_number($code);
        http_response_code($code);
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
    
    function writeJson($array)
    {
        assert_valid_array($array);
        header('Content-type: application/json');
        echo json_encode($array);
    }
    
    function writeFile($path)
    {
        $path = PATH_APP . $path;
        assert_valid_file($path);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $ftype = finfo_file($finfo, $path);
        finfo_close($finfo);
        $fp = fopen($path, 'rb');
        header("Content-Type: " . $ftype);
        header("Content-Length: " . filesize($path));
        fpassthru($fp);
    }

    function writeDownload($path, $name = NULL)
    {
        $path = PATH_APP . $path;
        assert_valid_file($path);
        if (!is_valid_string($name)) $name = basename($path);
        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Cache-Control: public"); // needed for internet explorer
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:".filesize($path));
        header("Content-Disposition: attachment; filename=" . $name);
        readfile($path);
    }

}
