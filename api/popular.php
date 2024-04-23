<?php
require_once("Models/Database.php");


$dbContext = new DBContext();
echo json_encode( $dbContext->getPopularCustomers());

?>