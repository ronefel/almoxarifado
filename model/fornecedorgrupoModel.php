<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/connection/Transaction.php';

class fornecedorgrupoModel extends Transaction {

    private $fornecedorgrupoid;
    private $nome;

    public function setFornecedorgrupoid($fornecedorgrupoid) {
        $this->fornecedorgrupoid = $fornecedorgrupoid;
    }

    public function getFornecedorgrupoid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->fornecedorgrupoid);
        } else {
            return addslashes($this->fornecedorgrupoid);
        }
    }

    public function setFornecedorgruponome($nome) {
        $this->nome = $nome;
    }

    public function getFornecedorgruponome($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->nome);
        } else {
            return addslashes($this->nome);
        }
    }

}

?>