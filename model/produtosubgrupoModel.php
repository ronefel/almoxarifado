<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/connection/Transaction.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/produtogrupoModel.php';

class produtosubgrupoModel extends Transaction {

    private $produtogrupomodel;
    private $produtosubgrupoid;
    private $produtogrupoid;
    private $nome;
    
    public function __construct() {
        $this->produtogrupomodel = new produtogrupoModel();
    }
    
    public function setProdutogrupomodel($produtogrupomodel){
        $this->produtogrupomodel = $produtogrupomodel;
    }
    
    public function getProdutogrupomodel(){
        return $this->produtogrupomodel;
    }
    
    public function setProdutosubgrupoid($produtosubgrupoid) {
        $this->produtosubgrupoid = $produtosubgrupoid;
    }

    public function getProdutosubgrupoid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->produtosubgrupoid);
        } else {
            return addslashes($this->produtosubgrupoid);
        }
    }
    
    public function getProdutogrupoid() {
        return $this->produtogrupoid;
    }

    public function setProdutogrupoid($produtogrupoid) {
        $this->produtogrupoid = $produtogrupoid;
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