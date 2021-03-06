<?php

defined('PATH_LITE') or exit('denied!');

class LRequest
{

    function readHead($key, $default)
    {
        $headers = getallheaders();
        foreach ($headers as $name => $value) {
            if (trim($key) == trim($name)) {
                return $value;
            }
        }
        return $default;
    }

    function readGet($key, $default)
    {
        $value = $default;
        $vars = explode("&", $_SERVER['QUERY_STRING']);
        for ($i = 0; $i < count($vars); $i++) {
            $pair = explode("=", $vars[$i]);
            if (count($pair) !== 2) {
                continue;
            }
            if ($pair[0] == $key && (gettype($pair[1]) == gettype($default) || $default == NULL)) {
                $value = $pair[1];
                break;
            }
        }
        return $value;
    }

    function readPost($key, $default)
    {
        if(isset($_POST[$key]) && (gettype($_POST[$key]) == gettype($default) || $default == NULL))
        {
            return $_POST[$key];
        }
        return $default;
    }

    function readInput()
    {
        return file_get_contents('php://input');
    }

    /**
     * type: image/gif|image/jpeg|image/pjpeg|image/png|image/x-png|image/bmp, audio/mpeg
     * parameters : inputKey, ["size" => 0, "type" => '', "path" => '', "name" => '',]
     */
    function readFile($key, $args = [])
    {
        $resultInfo['isOk'] = true;
        $resultInfo['errMsg'] = "";
        try {
            assert_or_throw(isset($_FILES[$key]), "file key not found");
            assert_or_throw(is_valid_string($args['type']), "file type not set");
            assert_or_throw(is_valid_number($args['size']), "file size not set");
            assert_or_throw(is_valid_string($args['path']), "file path not set");
            $file = $_FILES[$key];
            $type = $args['type'];
            $size = $args['size'];
            $path = $args['path'];
            assert_or_throw($file["error"] == 0, "file status error:" . $file["error"]); // 0:no error, 4:not selected
            assert_or_throw(file_exists($file["tmp_name"]), "file not exist");
            assert_or_throw(strpos($path, PATH_APP) === 0, "file path error");
            $name = md5_file($file["tmp_name"]) . ".upload";
            assert_or_throw(strpos($type, $file["type"]) !== false, "file type error");
            assert_or_throw($file["size"] < $size, "file size error:" . $file["size"] . "<" . $size);
            $ext = pathinfo($file["name"])['extension'];
            assert_or_throw(is_valid_string($ext), "file extension error");
            $fileName = $name . "." . $ext;
            $filePath = $path . $fileName;
            assert_or_throw(!file_exists($filePath), "file already exist:" . $filePath);
            tools_create_dir($path, true);
            move_uploaded_file($file["tmp_name"], $filePath);
            assert_or_throw(file_exists($filePath), "file move failed");
            $resultInfo['fileExt'] = $ext;
            $resultInfo['fileName'] = $name;
            $resultInfo['filePath'] = $filePath;
        } catch (Exception $exception) {
            tools_delete_dir(isset($_FILES[$key]) ? $_FILES[$key]["tmp_name"] : "");
            $resultInfo['isOk'] = false;
            $resultInfo['errMsg'] = $exception->getMessage();
        }
        return $resultInfo;
    }

}
