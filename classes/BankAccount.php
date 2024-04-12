<?php

class TooMuchPerTimeException extends Exception { 
 }

 class NotEnoughBalanceException extends Exception { 
}


class BankAccount{
    public $saldo;

    public function __construct(){
        $this->saldo = 1000;
    }

    function withdraw($belopp) {
        if($this->saldo < $belopp){
            throw new NotEnoughBalanceException("Belopp större än saldo");
        }
        if($belopp > 300){
            throw new TooMuchPerTimeException("Får inte ta ut mer än 300 per tillfälle");
        }

        $this->saldo = $this->saldo - $belopp;
    }
}
?>