<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/fornecedorgrupoModel.php';

class fornecedorModel extends fornecedorgrupoModel {

    protected $fornecedorid;
    protected $razao;
    protected $fantazia;
    protected $endereco;
    protected $numero;
    protected $bairro;
    protected $cidadeid;
    protected $cnpj_cpf;
    protected $inscricao_rg;
    protected $telefone;
    protected $contato;
    protected $datacadastro;
    protected $observacao;
    protected $ativo;
    protected $email;
    
    public function __construct() {
        $this->fornecedorgrupomodel = new fornecedorgrupoModel();
    }

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

    public function setRazao($razao) {
        $this->razao = $razao;
    }

    public function getRazao($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->razao);
        } else {
            return addslashes($this->razao);
        }
    }

    public function setFantazia($fantazia) {
        $this->fantazia = $fantazia;
    }

    public function getFantazia($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->fantazia);
        } else {
            return addslashes($this->fantazia);
        }
    }

    public function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    public function getEndereco($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->endereco);
        } else {
            return addslashes($this->endereco);
        }
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function getNumero($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->numero);
        } else {
            return addslashes($this->numero);
        }
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    public function getBairro($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->bairro);
        } else {
            return addslashes($this->bairro);
        }
    }

    public function setCidadeid($cidadeid) {
        $this->cidadeid = $cidadeid;
    }

    public function getCidadeid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->cidadeid);
        } else {
            return addslashes($this->cidadeid);
        }
    }

    public function setCnpj_cpf($cnpj_cpf) {
        $this->cnpj_cpf = $cnpj_cpf;
    }

    public function getCnpj_cpf($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->cnpj_cpf);
        } else {
            return addslashes($this->cnpj_cpf);
        }
    }

    public function setInscricao_rg($inscricao_rg) {
        $this->inscricao_rg = $inscricao_rg;
    }

    public function getInscricao_rg($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->inscricao_rg);
        } else {
            return addslashes($this->inscricao_rg);
        }
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function getTelefone($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->telefone);
        } else {
            return addslashes($this->telefone);
        }
    }

    public function setContato($contato) {
        $this->contato = $contato;
    }

    public function getContato($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->contato);
        } else {
            return addslashes($this->contato);
        }
    }

    public function setDatacadastro($datacadastro) {
        $this->datacadastro = $datacadastro;
    }

    public function getDatacadastro() {
        return $this->datacadastro;
    }

    public function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    public function getObservacao($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->observacao);
        } else {
            return addslashes($this->observacao);
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

}

?>