<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/connection/Transaction.php';

class marcaModel extends Transaction {

    private $marcaid;
    private $nome;

    public function setMarcaid($marcaid) {
        $this->marcaid = $marcaid;
    }

    public function getMarcaid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->marcaid);
        } else {
            return addslashes($this->marcaid);
        }
    }

    public function setMarcanome($nome) {
        $this->nome = $nome;
    }

    public function getMarcanome($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->nome);
        } else {
            return addslashes($this->nome);
        }
    }

}

?>