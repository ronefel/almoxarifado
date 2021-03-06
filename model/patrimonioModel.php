<?php
header ('Content-type: text/html; charset=UTF-8',true);

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/util/ExtensionBridge.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/fornecedorModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/departamentoModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/produtoModel.php';

class patrimonioModel extends ExtensionBridge {

    public $patrimonioid;
    protected $serie;
    protected $valor;
    protected $notafiscal;
    protected $datacompra;
    protected $fimgarantia;
    protected $dataimplantacao;
    protected $estadoconservacao;
    protected $obs;
    
    public function __construct() {
        parent::addExt(new fornecedorModel());
        parent::addExt(new departamentoModel());
        parent::addExt(new produtoModel());
    }

    public function setPatrimonioid($patrimonioid) {
        $this->patrimonioid = $patrimonioid;
    }

    public function getPatrimonioid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->patrimonioid);
        } else {
            return addslashes($this->patrimonioid);
        }
    }

    public function setSerie($serie) {
        $this->serie = $serie;
    }

    public function getSerie($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->serie);
        } else {
            return addslashes($this->serie);
        }
    }
    
    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getValor($format = "db") {
        if (strlen($this->valor) > 0) {
            if ($format == "form") {
                return number_format($this->valor, 2, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $this->valor);
            }
        } else {
            return $this->valor;
        }
    }

    public function setNotafiscal($notafiscal) {
        $this->notafiscal = $notafiscal;
    }

    public function getNotafiscal($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->notafiscal);
        } else {
            return addslashes($this->notafiscal);
        }
    }

    public function setDatacompra($datacompra) {
        $this->datacompra = $datacompra;
    }

    public function getDatacompra($br = FALSE) {
        if (strlen($this->datacompra) > 0) {
            if ($br) {
                return util::dateToBR($this->datacompra);
            } else {
                return $this->datacompra;
            }
        } else {
            return $this->datacompra;
        }
    }

    public function setFimgarantia($fimgarantia) {
        $this->fimgarantia = $fimgarantia;
    }

    public function getFimgarantia($br = FALSE) {
        if (strlen($this->fimgarantia) > 0) {
            if ($br) {
                return util::dateToBR($this->fimgarantia);
            } else {
                return $this->fimgarantia;
            }
        } else {
            return $this->fimgarantia;
        }
    }

    public function setDataimplantacao($dataimplantacao) {
        $this->dataimplantacao = $dataimplantacao;
    }

    public function getDataimplantacao($br = FALSE) {
        if (strlen($this->dataimplantacao) > 0) {
            if ($br) {
                return util::dateToBR($this->dataimplantacao);
            } else {
                return $this->dataimplantacao;
            }
        } else {
            return $this->dataimplantacao;
        }
    }

    public function setEstadoconservacao($estadoconservacao) {
        $this->estadoconservacao = $estadoconservacao;
    }

    public function getEstadoconservacao($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->estadoconservacao);
        } else {
            return addslashes($this->estadoconservacao);
        }
    }

    public function setObs($obs) {
        $this->obs = $obs;
    }

    public function getObs($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->obs);
        } else {
            return addslashes($this->obs);
        }
    }

}

?>