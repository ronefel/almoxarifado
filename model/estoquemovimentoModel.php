<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/util/ExtensionBridge.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/produtoModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/requisicaoModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/model/compraModel.php';

class estoquemovimentoModel extends ExtensionBridge {

    protected $estoquemovimentoid;
    protected $operacao;
    protected $operacaonome;
    protected $quantidade;
    protected $valorunitario;
    protected $estoquemovimentodata;
    protected $EM_produtoid;
    protected $totalentrada;
    protected $totalsaida;


    public function __construct() {
        parent::addExt(new produtoModel());
        parent::addExt(new requisicaoModel());
        parent::addExt(new compraModel());
    }

    public function setEstoquemovimentoid($estoquemovimentoid) {
        $this->estoquemovimentoid = $estoquemovimentoid;
    }

    public function getEstoquemovimentoid($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->estoquemovimentoid);
        } else {
            return addslashes($this->estoquemovimentoid);
        }
        return $this->estoquemovimentoid;
    }

    public function setOperacao($operacao) {
        $this->operacao = $operacao;
    }

    public function getOperacao($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->operacao);
        } else {
            return addslashes($this->operacao);
        }
    }

    public function setOperacaonome($operacaonome) {
        $this->operacaonome = $operacaonome;
    }

    public function getOperacaonome($tiraAspas = FALSE) {
        if ($tiraAspas) {
            return util::tiraAspas($this->operacaonome);
        } else {
            return addslashes($this->operacaonome);
        }
    }

    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }

    public function getQuantidade($format = "db") {
        if (strlen($this->quantidade) > 0) {
            if ($format == "form") {
                return number_format($this->quantidade, 3, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $this->quantidade);
            }
        } else {
            return $this->quantidade;
        }
    }

    public function setValorunitario($valorunitario) {
        $this->valorunitario = $valorunitario;
    }
    
    public function getValorunitario($format = "db") {
        if (strlen($this->valorunitario) > 0) {
            if ($format == "form") {
                return number_format($this->valorunitario, 2, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $this->valorunitario);
            }
        } else {
            return $this->valorunitario;
        }
    }
    
    public function getValortotal($format = "db") {
        $valortotal = "";
        if (strlen($this->valorunitario) > 0) {
            $valortotal = ($this->quantidade * $this->valorunitario);
            if ($format == "form") {
                return number_format($valortotal, 2, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $valortotal);
            }
        } else {
            return $valortotal;
        }
    }
    
    public function setEstoquemovimentodata($estoquemovimentodata) {
        $this->estoquemovimentodata = $estoquemovimentodata;
    }

    public function getEstoquemovimentodata($br = FALSE) {
        if (strlen($this->estoquemovimentodata) > 0) {
            if ($br) {
                return util::dateToBR($this->estoquemovimentodata);
            } else {
                return $this->estoquemovimentodata;
            }
        } else {
            return $this->estoquemovimentodata;
        }
    }
    
    public function getEM_produtoid() {
        return $this->EM_produtoid;
    }

    public function setEM_produtoid($EM_produtoid) {
        $this->EM_produtoid = $EM_produtoid;
    }

    public function setTotalentrada($totalentrada) {
        $this->totalentrada = $totalentrada;
    }

    public function getTotalentrada($format = "db") {
        if (strlen($this->totalentrada) > 0) {
            if ($format == "form") {
                return number_format($this->totalentrada, 3, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $this->totalentrada);
            }
        } else {
            return $this->totalentrada;
        }
    }
    
    public function setTotalsaida($totalsaida) {
        $this->totalsaida = $totalsaida;
    }

    public function getTotalsaida($format = "db") {
        if (strlen($this->totalsaida) > 0) {
            if ($format == "form") {
                return number_format($this->totalsaida, 3, ',', '');
            } else if ($format == "db") {
                return str_replace(',', '.', $this->totalsaida);
            }
        } else {
            return $this->totalsaida;
        }
    }


}

?>