<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/util/ExtensionBridge.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/departamentoModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/temaModel.php';

class usuarioModel extends ExtensionBridge {

    protected $usuarioid;
    protected $nome;
    protected $login;
    protected $senha;
    protected $ativo;
    protected $email;
    protected $tipousuario;
    
    public function __construct() {
        parent::addExt(new departamentoModel());
        parent::addExt(new temaModel());
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

    public function setUsuarionome($nome) {
        $this->nome = $nome;
    }

    public function getUsuarionome($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->nome);
        } else {
            return addslashes($this->nome);
        }
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getLogin($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->login);
        } else {
            return addslashes($this->login);
        }
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getSenha($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->senha);
        } else {
            return addslashes($this->senha);
        }
    }

    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    public function getAtivo($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->ativo);
        } else {
            return addslashes($this->ativo);
        }
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->email);
        } else {
            return addslashes($this->email);
        }
    }

    public function setTipousuario($tipousuario) {
        $this->tipousuario = $tipousuario;
    }

    public function getTipousuario($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->tipousuario);
        } else {
            return addslashes($this->tipousuario);
        }
    }
    
}

?>