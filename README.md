## LitePHP

* description

> this is a simple php framework for api development. copy files in `template` folder to your working folder, then do what u want in `index.php` ...

* usage

```php

// index.php/class/method/argument1/argument2

include_once("../Lite.php");
(new Lite())->run(function($app, $class, $method, $arguments) {
    echo 'hello...';
});

```
* more

> for more information check the files in `lite` folder ...
