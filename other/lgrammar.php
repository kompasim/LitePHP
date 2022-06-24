<?php

function isOddNum($num)
{
    return ($num % 2) ? TRUE : FALSE;
}

function isValidString($value)
{
    return is_string($value) && $value != "";
}

function isValidNumber($value)
{
    return is_numeric($value) && $value >= 0;
}

function isValidArray($value)
{
    return is_array($value) && count($value) > 0;
}

function to_string($value)
{
    if(is_bool($value))
    {
        return $value ? 'true' : 'false';
    } else if (is_null($value)) {
        return "null";
    } else {
        return (string)$value;
    }
}

function assertOrExit($isOk, $errMsg = "something went wrong ...")
{
    if ($isOk) return;
    if (defined('IS_DEBUG') && IS_DEBUG) {
        throw new RuntimeException($errMsg);
    } else {
        exit($errMsg);
    }
}

function assertOrPrint($isOk, $errMsg = "something went wrong ...")
{
    if ($isOk) return;
    exit($errMsg);
}

function assertOrThrow($isOk, $errMsg = "something went wrong ...", $errorCode = -1111)
{
    if ($isOk) return;
    throw new RuntimeException("[" . $errMsg, $errorCode);
    exit;
}

function assertNumberExit($value)
{
    assertOrExit(isValidNumber($value), "should be a valid number!");
}

function assertStringExit($value)
{
    assertOrExit(isValidString($value), "should be a valid string!");
}

function assertArrayExit($value)
{
    assertOrExit(isValidArray($value), "should be a valid array!");
}

function assertFileExit($value)
{
    assertOrExit(isValidString($value) && file_exists($value), "should be a valid path!");
}
