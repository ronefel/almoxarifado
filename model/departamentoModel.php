<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/localModel.php';

class departamentoModel extends localModel {

    private $departamentoid;
    private $nome;

    public function setDepartamentoid($departamentoid) {
        $this->departamentoid = $departamentoid;
    }

    public function getDepartamentoid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->departamentoid);
        } else {
            return addslashes($this->departamentoid);
        }
    }

    public function setDepartamentonome($nome) {
        $this->nome = $nome;
    }

    public function getDepartamentonome($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->nome);
        } else {
            return addslashes($this->nome);
        }
    }

}

?>