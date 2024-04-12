

<?php
require_once("classes/BankAccount.php");

// OM GET -> visa formuläret
// OM POST -> dom har fyllt i formuläret och tryckt på spara

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    try{
        $belopp = $_POST['belopp'];
        $bankAccount = new BankAccount();
        $bankAccount->withdraw($belopp);
        //Olika typer av fel kan nu inträffa
        $message = "Allt gick bra pengarna är uttagna - kvar är $bankAccount->saldo ";
    }
    catch(NotEnoughBalanceException  $e){
        $message = "Du har inte så mycket på kontot - kvar är $bankAccount->saldo ";
    }
    catch(TooMuchPerTimeException  $e){
        $message = "Får inte ta ut så mycket max 300 per gång - kvar är $bankAccount->saldo ";
    }
    catch(Exception $e){
        $message = "Knas - kvar är $bankAccount->saldo ";
    }
}

//$namn = $_POST['namn'];
?>
<html>
    <body>
        <?php echo $message; ?>
        <h1>UTTAG</h1>
        <form method="POST"> 
            <input type="text" name="belopp" />
            <input type="submit" value="Ta ut" />
        </form>
    </body>
</html>