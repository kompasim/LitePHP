<?php

defined('PATH_LITE') or exit('denied!');

class App extends LApplication {

    function __construct()
    {
        parent::__construct();
        $this->initCookie();
        $this->initSession();
        // $image = $app->loadThrid('./Image.php', '\claviska\SimpleImage');
        // $http = $app->loadThrid('./Http.php', 'Http');
        // $medoo = $app->loadThrid('./Medoo.php', '\Medoo\Medoo', [
        //     'database_type' => 'sqlite',
        //     'database_file' => ':memory:'
        // ]);
    }

    function default()
    {
        echo "Hello LitePHP!";
    }

    function empty()
    {
        echo "404!";
    }

    function hello($arg = "Someone")
    {
        echo "Hello, this is $arg!";
    }

    function hi()
    {
        $arg = $this->request->readGet("name", "Someone");
        $this->response->writeText("Hi, this is $arg!");
    }

}
