<?php
require_once("Migrations/migrationBase.php");

class AddCountryToOffice extends MigrationBase{
    function up(){
        $this->pdo->exec("alter table Office add column countrycode char(3) null");
    }
}
