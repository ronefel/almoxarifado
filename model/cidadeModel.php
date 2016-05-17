<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/connection/Transaction.php';

class cidadeModel extends Transaction {

    private $cidadeid;
    private $nome;
    private $uf;
    private $cep;

    public function getCidadeid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->cidadeid);
        } else {
            return addslashes($this->cidadeid);
        }
    }

    public function getNome($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->nome);
        } else {
            return addslashes($this->nome);
        }
    }

    public function getUf($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->uf);
        } else {
            return addslashes($this->uf);
        }
    }

    public function getCep($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->cep);
        } else {
            return addslashes($this->cep);
        }
    }

    public function setCidadeid($cidadeid) {
        $this->cidadeid = $cidadeid;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setUf($uf) {
        $this->uf = $uf;
    }

    public function setCep($cep) {
        $this->cep = $cep;
    }

}

?>