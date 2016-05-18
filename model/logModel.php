<?php

class logModel {

    private $logid;
    private $usuarioid;
    private $descricao;
    private $data;

    public function setLogid($logid) {
        $this->logid = $logid;
    }

    public function getLogid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->logid);
        } else {
            return addslashes($this->logid);
        }
    }

    public function setUsuarioid($usuarioid) {
        $this->usuarioid = $usuarioid;
    }

    public function getUsuarioid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->usuarioid);
        } else {
            return addslashes($this->usuarioid);
        }
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getDescricao($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->descricao);
        } else {
            return addslashes($this->descricao);
        }
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getData($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->data);
        } else {
            return addslashes($this->data);
        }
    }

}

?>