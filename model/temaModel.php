<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/connection/Transaction.php';

class temaModel extends Transaction {

    private $temaid;
    private $nome;
    private $link;
    private $img;

    public function getTemaid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->temaid);
        } else {
            return addslashes($this->temaid);
        }
    }

    public function getNome($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->nome);
        } else {
            return addslashes($this->nome);
        }
    }

    public function getLink($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->link);
        } else {
            return addslashes($this->link);
        }
    }
    
    public function getImg($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->img);
        } else {
            return addslashes($this->img);
        }
    }

    public function setTemaid($temaid) {
        $this->temaid = $temaid;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setLink($uf) {
        $this->link = $uf;
    }
    
    public function setImg($img) {
        $this->img = $img;
    }

}

?>