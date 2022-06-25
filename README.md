## LitePHP

* description

> this is a simple php framework for api development. copy files in `template` folder to your working folder, then do what u want in `index.php` ...

* usage

> handle url `index.php/class/method/argument1/argument2`

```php
include_once("../Lite.php");
(new Lite())->run(function($app, $class, $method, $arguments) {
    echo 'hello...';
});
```

* more

> for more information check the files in `lite` folder ...

* [Medoo](https://github.com/catfan/Medoo)
* [Image](https://github.com/claviska/SimpleImage)
* [Http](https://github.com/summerblue/http-class-for-php)
* [QRCode](https://sourceforge.net/projects/phpqrcode/)
* [Captcha](https://github.com/lifei6671/php-captcha)
