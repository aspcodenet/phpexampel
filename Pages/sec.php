<?php
// http://localhost:8000/sec?id=1;%20DELETE+from+Customer+where+id+=+2

// AVOID: 
// ANVÄND prepared query och PARAMETRAR
// fast med offset/limit/orderby???

//Offset och limit = intval()
// orderby och orderdesc
// $orderdesc = $_GET['orderdesc'] === 'DESC' ? 'DESC' : 'ASC';
// Order by


// https://www.php.net/manual/en/security.database.sql-injection.php


function oneOf($value, $arr,$default){
    foreach($arr as $a){
        if(strcasecmp($a, $value) == 0 ){
            return $a;
        }
    }
    return $default;
}


require_once("Models/Database.php");
?>
<html>
    <body>
    <?php


echo oneOf("varde",["name","city","postal"],"id");
echo "<br/>";
echo oneOf("city",["name","city","postal"],"id");
echo "<br/>";
echo oneOf("PostaL",["name","city","postal"],"id");


$id = $_GET['id'] ?? "";
echo $id;
$db = new DBContext();


$prep = $db->pdo->prepare("SELECT * FROM Customer where id=$id");

//$prep->setFetchMode(PDO::FETCH_CLASS,'Customer');
$prep->execute();
$c =   $prep->fetch();


$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $message = "Hej " . $userId;
}

?>
<h3><?php var_dump($c);?></h3>
<h3><?php echo $c["Surname"];?></h3>
        <?php echo $message; ?>
        <h1>Vad heter du?</h1>
        <form method="POST"> 
            <input type="text" name="name" />
            <input type="submit" value="Skicka" />
        </form>
    </body>
</html>