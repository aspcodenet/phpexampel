<?php

require_once('vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once(dirname(__FILE__) ."/Utils/Router.php");

$router = new Router();
$router->addRoute('/', function () {
    require __DIR__ .'/Pages/index.php';
});
$router->addRoute('/customer', function () {
    require __DIR__ .'/Pages/customer.php';
});
$router->addRoute('/office', function () {
    require __DIR__ .'/Pages/office.php';
});

$router->dispatch();
?>

// extension=openssl