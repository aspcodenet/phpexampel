<?php
require_once("Migrations/202404091227-AddCountryToOffice.php");

class Migrator {
    public $pdo;
    public $migrations;
     
    function __construct($pdo){
        $this->pdo = $pdo;
        $this->migrations = [
            new AddCountryToOffice($this->pdo)
        ];

        $this->pdo->exec('create table if not exists migration (name varchar(255))');
        $arr = $pdo->query("select * from migration")->fetchAll(PDO::FETCH_COLUMN);        



        foreach( $this->migrations as $migration ) {
            if(!in_array($migration::class,$arr)){
                $migration->up();
                $this->pdo->prepare('insert into migration values (:name)')
                    ->execute(['name' => $migration::class]);                                        
            }

            

        }
        

    }

};


class MigrationBase
{
    public $pdo;
    function __construct($pdo){
        $this->pdo = $pdo;
    }

    function up() {

    }
};



?>