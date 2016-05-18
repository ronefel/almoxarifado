<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/usuarioModel.php';

class requisicaoModel extends usuarioModel {

    protected $requisicaoid;
    protected $emissao;
    protected $aprovacao;
    protected $entrega;
    protected $situacao;
    protected $situacaonome;
    protected $reprovacaotxt;
    protected $reprovacao;
    protected $requisicaovalortotal;

    public function setRequisicaoid($requisicaoid) {
        $this->requisicaoid = $requisicaoid;
    }

    public function getRequisicaoid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->requisicaoid);
        } else {
            return addslashes($this->requisicaoid);
        }
    }

    public function setRequisicaoemissao($emissao) {
        $this->emissao = $emissao;
    }

    public function getRequisicaoemissao($br = FALSE) {
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

    public function setRequisicaoaprovacao($aprovacao) {
        $this->aprovacao = $aprovacao;
    }

    public function getRequisicaoaprovacao($br = FALSE) {
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

    public function setRequisicaoentrega($entrega) {
        $this->entrega = $entrega;
    }

    public function getRequisicaoentrega($br = FALSE) {
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

    public function setRequisicaosituacao($situacao) {
        $this->situacao = $situacao;
    }

    public function getRequisicaosituacao($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->situacao);
        } else {
            return addslashes($this->situacao);
        }
    }

    public function getRequisicaosituacaonome() {
        return $this->situacaonome;
    }

    public function setRequisicaosituacaonome($situacaonome) {
        $this->situacaonome = $situacaonome;
    }
    
    public function setRequisicaoreprovacaotxt($reprovacaotxt) {
        $this->reprovacaotxt = $reprovacaotxt;
    }

    public function getRequisicaoreprovacaotxt($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->reprovacaotxt);
        } else {
            return addslashes($this->reprovacaotxt);
        }
    }
    
    public function setRequisicaoreprovacao($reprovacao) {
        $this->reprovacao = $reprovacao;
    }

    public function getRequisicaoreprovacao($br = FALSE) {
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
    
    public function setRequisicaovalortotal($requisicaovalortotal) {
        $this->requisicaovalortotal = $requisicaovalortotal;
    }
    
    public function getRequisicaovalortotal($format = "db") {
        if (strlen($this->requisicaovalortotal) > 0) {
            if ($format == "form") {
                return number_format($this->requisicaovalortotal, 2, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $this->requisicaovalortotal);
            }
        } else {
            return $this->requisicaovalortotal;
        }
    }

}

?>