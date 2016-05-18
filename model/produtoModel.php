<?php
header ('Content-type: text/html; charset=UTF-8',true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/connection/Transaction.php';

class produtoModel extends Transaction {

    public $produtoid;
    protected $produtosubgrupoid;
    protected $produtogrupoid;
    protected $produtonome;
    protected $und;
    protected $customedio;
    protected $valormedio;
    protected $codigobarras;
    protected $validade;
    protected $observacoes;
    protected $ativo;
    protected $estoqueminimo;
    protected $estoquemaximo;
    protected $estoqueatual;

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

    public function setProdutonome($produtonome) {
        $this->produtonome = $produtonome;
    }

    public function getProdutonome($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->produtonome);
        } else {
            return addslashes($this->produtonome);
        }
    }

    public function setUnd($und) {
        $this->und = $und;
    }

    public function getUnd($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->und);
        } else {
            return addslashes($this->und);
        }
    }

    public function setCustomedio($customedio) {
        $this->customedio = $customedio;
    }

    public function getCustomedio($format = "db") {
        if (strlen($this->customedio) > 0) {
            if ($format == "form") {
                return number_format($this->customedio, 2, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $this->customedio);
            }
        } else {
            return $this->customedio;
        }
    }
    
    public function setValormedio($valormedio) {
        $this->valormedio = $valormedio;
    }

    public function getValormedio($format = "db") {
        if (strlen($this->valormedio) > 0) {
            if ($format == "form") {
                return number_format($this->valormedio, 2, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $this->valormedio);
            }
        } else {
            return $this->valormedio;
        }
    }

    public function setCodigobarras($codigobarras) {
        $this->codigobarras = $codigobarras;
    }

    public function getCodigobarras($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->codigobarras);
        } else {
            return addslashes($this->codigobarras);
        }
    }

    public function setValidade($validade) {
        $this->validade = $validade;
    }

    public function getValidade($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->validade);
        } else {
            return addslashes($this->validade);
        }
    }

    public function setObservacoes($observacoes) {
        $this->observacoes = $observacoes;
    }

    public function getObservacoes($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->observacoes);
        } else {
            return addslashes($this->observacoes);
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

    public function setEstoqueminimo($estoqueminimo) {
        $this->estoqueminimo = $estoqueminimo;
    }

    public function getEstoqueminimo($format = "db") {
        if (strlen($this->estoqueminimo) > 0) {
            if ($format == "form") {
                return number_format($this->estoqueminimo, 3, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $this->estoqueminimo);
            }
        } else {
            return $this->estoqueminimo;
        }
    }

    public function setEstoquemaximo($estoquemaximo) {
        $this->estoquemaximo = $estoquemaximo;
    }

    public function getEstoquemaximo($format = "db") {
        if (strlen($this->estoquemaximo) > 0) {
            if ($format == "form") {
                return number_format($this->estoquemaximo, 3, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $this->estoquemaximo);
            }
        } else {
            return $this->estoquemaximo;
        }
    }

    public function setEstoqueatual($estoqueatual) {
        $this->estoqueatual = $estoqueatual;
    }

    public function getEstoqueatual($format = "db") {
        if (strlen($this->estoqueatual) > 0) {
            if ($format == "form") {
                return number_format($this->estoqueatual, 3, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $this->estoqueatual);
            }
        } else {
            return $this->estoqueatual;
        }
    }

}

?>