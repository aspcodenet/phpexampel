<?php
require 'vendor/autoload.php';
require_once('Models/Database.php');
require_once("Pages/layout/header.php");
require_once("Pages/layout/sidenav.php");
require_once("Pages/layout/footer.php");


$dbContext = new DBContext();

$dbContext->getUsersDatabase()->getAuth()->logOut();
header('Location: /');
exit;