<?php
// globala initieringar !
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
$router->addRoute('/viewcustomer', function () {
    require __DIR__ .'/Pages/viewcustomer.php';
});
$router->dispatch();
?>