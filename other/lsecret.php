<?php

function encodeText($text)
{
    $str = base64_encode($text);
    $arr = str_split($str);
    for ($i = 0; $i <  count($arr); $i = $i + 2) {
        if (isset($arr[$i + 1])) {
            $temp = $arr[$i + 1];
            $arr[$i + 1] = $arr[$i];
            $arr[$i] = $temp;
        }
    }
    $cipher = base64_encode(implode("", $arr));
    return $cipher;
}

function decodeText($cipher)
{
    $str = base64_decode($cipher);
    $arr = str_split($str);
    for ($i = 0; $i <  count($arr); $i = $i + 2) {
        if (isset($arr[$i + 1])) {
            $temp = $arr[$i + 1];
            $arr[$i + 1] = $arr[$i];
            $arr[$i] = $temp;
        }
    }
    $text = base64_decode(implode("", $arr));
    return $text;
}

function getStringAlias($text)
{
    return md5(encodeText($text));
}

function getStringSession($text)
{
    return md5($text . md5(time()));
}

function getStringSecret($text, $secret = "")
{
    return md5($text . md5($secret));
}

function setLiteLocked()
{
    file_put_contents(PATH_APP . "lite.lock", date("Y-m-d_H-i-s"));
}

function isLiteLocked()
{
    return is_file(PATH_APP . "lite.lock");
}

function checkLiteLocked()
{
    isLiteLocked() or exit("lite not installed");
}
