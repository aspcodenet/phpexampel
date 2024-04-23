<?php
// globala initieringar !
require_once(dirname(__FILE__) ."/Utils/Router.php");
require_once("vendor/autoload.php");

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();
$router->addRoute('/', function () {
    require __DIR__ .'/Pages/index.php';
});

$router->addRoute('/customer', function () {
    require __DIR__ .'/Pages/customer.php';
});

$router->addRoute('/newcustomer', function () {
    require(__DIR__ .'/Pages/newcustomer.php');
});


$router->addRoute('/office', function () {
    require __DIR__ .'/Pages/office.php';
});

$router->addRoute('/uttag', function () {
    require __DIR__ .'/Pages/form.php';
});

$router->addRoute('/popular', function () {
    require __DIR__ .'/Pages/popular.php';
});

$router->addRoute('/test', function () {
    require __DIR__ .'/Pages/newdesign.php';
});



$router->addRoute('/viewcustomer', function () {
    require __DIR__ .'/Pages/viewcustomer.php';
});

$router->addRoute('/admin', function () {
    require __DIR__ .'/Pages/admin.php';
});

$router->addRoute('/user/login', function () {
    require __DIR__ .'/Pages/users/login.php';
});

$router->addRoute('/user/logout', function () {
    require __DIR__ .'/Pages/users/logout.php';
});

$router->addRoute('/user/register', function () {
    require __DIR__ .'/Pages/users/register.php';
});

$router->addRoute('/user/registerthanks', function () {
    require __DIR__ .'/Pages/users/registerthanks.php';
});


$router->addRoute('/popular2', function () {
    require __DIR__ .'/Pages/popular2.php';
});

$router->addRoute('/json/popular', function () {
    require __DIR__ .'/api/popular.php';
});


$router->addRoute('/addtocart', function () {
    require __DIR__ .'/api/addtocart.php';
});


$router->dispatch();
?>