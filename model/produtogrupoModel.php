<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/connection/Transaction.php';

class produtogrupoModel extends Transaction {

    private $produtogrupoid;
    private $nome;

    public function setProdutogrupoid($produtogrupoid) {
        $this->produtogrupoid = $produtogrupoid;
    }

    public function getProdutogrupoid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->produtogrupoid);
        } else {
            return addslashes($this->produtogrupoid);
        }
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getNome($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->nome);
        } else {
            return addslashes($this->nome);
        }
    }

}

?>