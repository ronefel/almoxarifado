<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/connection/Transaction.php';

class localModel extends Transaction {

    private $localid;
    private $nome;

    public function setLocalid($localid) {
        $this->localid = $localid;
    }

    public function getLocalid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->localid);
        } else {
            return addslashes($this->localid);
        }
    }

    public function setLocalnome($nome) {
        $this->nome = $nome;
    }

    public function getLocalnome($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->nome);
        } else {
            return addslashes($this->nome);
        }
    }

}

?>