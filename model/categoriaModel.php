<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/connection/Transaction.php';

class categoriaModel extends Transaction {

    private $categoriaid;
    private $nome;

    public function setCategoriaid($categoriaid) {
        $this->categoriaid = $categoriaid;
    }

    public function getCategoriaid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->categoriaid);
        } else {
            return addslashes($this->categoriaid);
        }
    }

    public function setCategorianome($nome) {
        $this->nome = $nome;
    }

    public function getCategorianome($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->nome);
        } else {
            return addslashes($this->nome);
        }
    }

}

?>