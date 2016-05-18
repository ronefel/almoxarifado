<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/fornecedorModel.php';

class compraModel extends fornecedorModel {

    protected $compraid;
    protected $emissao;
    protected $aprovacao;
    protected $entrega;
    protected $situacao;
    protected $situacaonome;
    protected $reprovacaotxt;
    protected $reprovacao;
    protected $compravalortotal;

    public function setCompraid($compraid) {
        $this->compraid = $compraid;
    }

    public function getCompraid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->compraid);
        } else {
            return addslashes($this->compraid);
        }
    }

    public function setCompraemissao($emissao) {
        $this->emissao = $emissao;
    }

    public function getCompraemissao($br = FALSE) {
        if (strlen($this->emissao) > 0) {
            if ($br) {
                return util::dateToBR($this->emissao);
            } else {
                return $this->emissao;
            }
        } else {
            return $this->emissao;
        }
    }

    public function setCompraaprovacao($aprovacao) {
        $this->aprovacao = $aprovacao;
    }

    public function getCompraaprovacao($br = FALSE) {
        if (strlen($this->aprovacao) > 0) {
            if ($br) {
                return util::dateToBR($this->aprovacao);
            } else {
                return $this->aprovacao;
            }
        } else {
            return $this->aprovacao;
        }
    }

    public function setCompraentrega($entrega) {
        $this->entrega = $entrega;
    }

    public function getCompraentrega($br = FALSE) {
        if (strlen($this->entrega) > 0) {
            if ($br) {
                return util::dateToBR($this->entrega);
            } else {
                return $this->entrega;
            }
        } else {
            return $this->entrega;
        }
    }

    public function setComprasituacao($situacao) {
        $this->situacao = $situacao;
    }

    public function getComprasituacao($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->situacao);
        } else {
            return addslashes($this->situacao);
        }
    }

    public function getComprasituacaonome() {
        return $this->situacaonome;
    }

    public function setComprasituacaonome($situacaonome) {
        $this->situacaonome = $situacaonome;
    }
    
    public function setComprareprovacaotxt($reprovacaotxt) {
        $this->reprovacaotxt = $reprovacaotxt;
    }

    public function getComprareprovacaotxt($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->reprovacaotxt);
        } else {
            return addslashes($this->reprovacaotxt);
        }
    }
    
    public function setComprareprovacao($reprovacao) {
        $this->reprovacao = $reprovacao;
    }

    public function getComprareprovacao($br = FALSE) {
        if (strlen($this->reprovacao) > 0) {
            if ($br) {
                return util::dateToBR($this->reprovacao);
            } else {
                return $this->reprovacao;
            }
        } else {
            return $this->reprovacao;
        }
    }
    
    public function setCompravalortotal($compravalortotal) {
        $this->compravalortotal = $compravalortotal;
    }
    
    public function getCompravalortotal($format = "db") {
        if (strlen($this->compravalortotal) > 0) {
            if ($format == "form") {
                return number_format($this->compravalortotal, 2, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $this->compravalortotal);
            }
        } else {
            return $this->compravalortotal;
        }
    }

}

?>