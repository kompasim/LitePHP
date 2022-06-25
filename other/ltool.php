<?php

function checkLiteEnv()
{
    defined('PATH_LITE') or exit('lite not found!');
}

function deleteSomethingSafely($dir)
{
    if (!is_string($dir)) {
        return true;
    }
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!deleteSomethingSafely($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    return rmdir($dir);
}

function getDateString()
{
    return date("Y-m-d_H-i-s");
}

function createNewDir($path, $skipIfExist = false)
{
    if (is_dir($path) && !$skipIfExist) {
        deleteSomethingSafely($path);
    }
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

function execSomeCmd($cmd)
{
    $cmd = sprintf("%s 2>&1", $cmd);
    exec($cmd, $output, $status);
    $cmdResult["isOk"] = $status == 0;
    $cmdResult["cmdMsg"] = $output;
    return $cmdResult;
}

function doClipFile($sourcePath, $targetPath, $lengthRate = 1)
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

function printNewLine($value = null)
{
    echo "<br>";
    if ($value == null) return;
    if (isValidString($value) || isValidNumber($value)) {
        echo $value;
    } else {
        var_export($value);
    }
}

function redirectAndExit($url)
{
    echo "<script>window.location.href='" . $url . "';</script>";
    exit;
}

function downloadAndSave($url, $path)
{
    $ext = pathinfo($url)['extension'];
    $content = @file_get_contents($url);
    $headers = $http_response_header;
    for ($i = 0; $i < count($headers); $i++) {
        $header = $headers[$i];
        if (strpos($header, "Location") === 0) {
            if (strpos($header, $ext) != (strlen($header) - strlen($ext))) {
                return false;
            }
        }
    }
    file_put_contents($path, $content);
    return file_exists($path);
}

function setLiteLocked()
{
    file_put_contents(PATH_APP . "lite.lock", getDateString());
}

function isLiteLocked()
{
    return is_file(PATH_APP . "lite.lock");
}

function checkLiteLocked()
{
    isLiteLocked() or exit("lite not installed");
}

function httpRequest($url, $getBody = ['empty' => true], $postBody = ['empty' => true], $headers = [], $timeOut = 30)
{
    if (is_array($getBody)) $getBody = http_build_query($getBody);
    if (is_array($postBody)) $postBody = http_build_query($postBody);
    assertOrPrint(is_string($getBody), "invalid get body!");
    assertOrPrint(is_string($postBody), "invalid post body!");
    assertOrPrint(is_array($headers), "invalid header body!");
    $curl = curl_init();
    if (isValidArray($headers)) {
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    if (isValidString($postBody)) {
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postBody);
    }
    if (isValidString($getBody)) {
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

function getScriptUrl($isFull = false)
{
    $url = $_SERVER['SERVER_ADDR'] . $_SERVER['SCRIPT_NAME'];
    if ($isFull) return $url;
    return str_replace("index.php", "", strtolower($_SERVER['SERVER_ADDR'] . $_SERVER['SCRIPT_NAME']));
}
