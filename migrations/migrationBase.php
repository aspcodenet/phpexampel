<?php




class Migrator {
    public $pdo;
    public $migrations;
     
    function __construct($pdo){
        $this->pdo = $pdo;
        $this->migrations = [
            new AddRegNoToCustomer($this->pdo),
            new AddPlayer($this->pdo)
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

class AddRegNoToCustomer extends MigrationBase {
    public function up() {
        $this->pdo->exec("alter table Customer add regno  varchar(10);");
        //$this->pdo->exec("delete from Customer;");
    }
}

class AddPlayer  extends MigrationBase {
    public function up() {
        $sql  ="CREATE TABLE IF NOT EXISTS `Player` (
            `Id` INT AUTO_INCREMENT NOT NULL,
            `Name` varchar(200) NOT NULL,
            PRIMARY KEY (`id`)
            ) ";

         $this->pdo->exec($sql);
        //$this->pdo->exec("delete from Customer;");
    }
}


?>