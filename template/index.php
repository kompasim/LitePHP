<?php

include_once('./config.php');

$programPath = PATH_LITE . "Lite.php";
file_exists($programPath) || exit("LitePHP not found!");
include_once($programPath);

$lite = new Lite();
$lite->run();
