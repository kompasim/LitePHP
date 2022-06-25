<?php

function is_valid_string($value)
{
    return is_string($value) && $value != "";
}

function is_valid_number($value)
{
    return is_numeric($value) && $value >= 0;
}

function is_valid_array($value)
{
    return is_array($value) && count($value) > 0;
}

function assert_or_print($isOk, $errMsg = "something went wrong ...")
{
    if (!$isOk) echo $errMsg;
}

function assert_or_exit($isOk, $errMsg = "something went wrong ...")
{
    if ($isOk) return;
    if (defined('IS_DEBUG') && IS_DEBUG) throw new RuntimeException($errMsg);
    exit($errMsg);
}

function assert_or_throw($isOk, $errMsg = "something went wrong ...", $errorCode = 0)
{
    if (!$isOk) throw new RuntimeException("[" . $errMsg . "]", $errorCode);
}

function assert_valid_number($value)
{
    assert_or_exit(is_valid_number($value), "should be a valid number!");
}

function assert_valid_string($value)
{
    assert_or_exit(is_valid_string($value), "should be a valid string!");
}

function assert_valid_array($value)
{
    assert_or_exit(is_valid_array($value), "should be a valid array!");
}

function assert_valid_file($value)
{
    assert_or_exit(is_valid_string($value) && file_exists($value), "should be a valid path!");
}

function tools_delete_dir($dir)
{
    if (!is_string($dir)) return true;
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!tools_delete_dir($dir . DIRECTORY_SEPARATOR . $item)) return false;
    }
    return rmdir($dir);
}

function tools_create_dir($path, $skipIfExist = false)
{
    if (is_dir($path) && !$skipIfExist) tools_delete_dir($path);
    if (!is_dir($path)) mkdir($path, 0777, true);
}

function tools_execute_cmd($cmd)
{
    $cmd = sprintf("%s 2>&1", $cmd);
    exec($cmd, $output, $status);
    $cmdResult["isOk"] = $status == 0;
    $cmdResult["cmdMsg"] = $output;
    return $cmdResult;
}

function tools_copy_file($sourcePath, $targetPath, $lengthRate = 1)
{
    $size = filesize($sourcePath);
    $start = 0;
    $length = floor($size * $lengthRate);
    $sourceFile = fopen($sourcePath, 'rb');
    fseek($sourceFile, $start);
    $content = fread($sourceFile, $length);
    $targetFile = fopen($targetPath, 'wb');
    fwrite($targetFile, $content);
    fclose($targetFile);
    fclose($sourceFile);
    return file_exists($sourcePath) && file_exists($targetPath);
}

function tools_download_file($url, $path)
{
    $ext = pathinfo($url)['extension'];
    $content = @file_get_contents($url);
    $headers = $http_response_header;
    for ($i = 0; $i < count($headers); $i++) {
        $header = $headers[$i];
        if (strpos($header, "Location") !== 0) continue;
        if (strpos($header, $ext) != (strlen($header) - strlen($ext))) return false;
    }
    file_put_contents($path, $content);
    return file_exists($path);
}

function tools_http_request($url, $getBody = ['empty' => true], $postBody = ['empty' => true], $headers = [], $timeOut = 30)
{
    if (is_array($getBody)) $getBody = http_build_query($getBody);
    if (is_array($postBody)) $postBody = http_build_query($postBody);
    assert_or_exit(is_string($getBody), "invalid get body!");
    assert_or_exit(is_string($postBody), "invalid post body!");
    assert_or_exit(is_array($headers), "invalid header body!");
    $curl = curl_init();
    if (is_valid_array($headers)) {
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    if (is_valid_string($postBody)) {
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postBody);
    }
    if (is_valid_string($getBody)) {
        $url = $url . "?" . $getBody;
    }
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    curl_setopt($curl, CURLOPT_MAXREDIRS, 4);
    curl_setopt($curl, CURLOPT_ENCODING, ""); //必须解压缩防止乱码
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.1; zh-CN) AppleWebKit/535.12 (KHTML, like Gecko) Chrome/22.0.1229.79 Safari/535.12");
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeOut);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

function tools_get_script($isFull = false)
{
    $url = $_SERVER['SERVER_ADDR'] . $_SERVER['SCRIPT_NAME'];
    if ($isFull) return $url;
    return str_replace("index.php", "", strtolower($_SERVER['SERVER_ADDR'] . $_SERVER['SCRIPT_NAME']));
}

function tools_mew_class()
{
    assert_or_exit(func_num_args() >= 2, 'too few arguments');
    $arguments = func_get_args();
    $path = $arguments[0];
    $class = $arguments[1];
    $params = array_slice($arguments, 2, count($arguments) - 2);
    require_once $path;
    assert_or_exit(class_exists($class, false), 'class not found!');
    return new $class(...$params);
}
