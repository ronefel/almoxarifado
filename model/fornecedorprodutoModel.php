<?php

class fornecedorprodutoModel {

    private $fornecedorid;
    private $produtoid;

    public function setFornecedorid($fornecedorid) {
        $this->fornecedorid = $fornecedorid;
    }

    public function getFornecedorid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->fornecedorid);
        } else {
            return addslashes($this->fornecedorid);
        }
    }

    public function setProdutoid($produtoid) {
        $this->produtoid = $produtoid;
    }

    public function getProdutoid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->produtoid);
        } else {
            return addslashes($this->produtoid);
        }
    }

}

?>