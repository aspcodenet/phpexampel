

<?php
// OM GET -> visa formuläret
// OM POST -> dom har fyllt i formuläret och tryckt på spara

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $namn = $_POST['namn'];
    $age = $_POST['age'];
    //Validera
    //Spara i databasen
    $message = "$namn och $age har sparas i databasen";
}

//$namn = $_POST['namn'];
?>
<html>
    <body>
        <?php echo $message; ?>
        <h1><?php echo $_SERVER['REQUEST_METHOD']; ?>
        <form method="POST">
            <input type="text" name="namn" />
            <input type="text" name="age" />
            <input type="submit" value="Spara" />
        </form>
    </body>
</html>