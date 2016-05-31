<?php

include_once '../config.php';

require_once '../action/patrimonioAction.php';

$patrimonio = new patrimonioModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['patrimonioid'])) {
    if (is_numeric($_POST['patrimonioid'])) {
        $patrimonio->setPatrimonioid($_POST['patrimonioid']);
    }
}

switch ($control) {

    case 'consulta': {

            if (strlen($patrimonio->getPatrimonioid()) > 0) {

                $patrimonio = patrimonioAction::getPatrimonio($patrimonio);

                if (strlen($patrimonio->getPatrimonioid()) > 0) {

                    $JSON = "{\"Patrimônio\":\"{$patrimonio->getPatrimonioid()}\","
                            . "\"Produto\":\"{$patrimonio->getProdutonome()}\","
                            . "\"Local\":\"{$patrimonio->getLocalnome()}\","
                            . "\"Departamento\":\"{$patrimonio->getDepartamentonome()}\","
                            . "\"Data da Compra\":\"{$patrimonio->getDatacompra(TRUE)}\","
                            . "\"Data Implantação\":\"{$patrimonio->getDataimplantacao(TRUE)}\","
                            . "\"Fim da Garantia\":\"{$patrimonio->getFimgarantia(TRUE)}\","
                            . "\"Estado de Conservação\":\"{$patrimonio->getEstadoconservacao()}\","
                            . "\"Fornecedor\":\"{$patrimonio->getFantazia()}\","
                            . "\"Observação\":\"{$patrimonio->getObs()}\"}";

                    $msg[] = $JSON;
                } else {
                    $msg[] = "Patrimônio não encontrado!";
                }
            } else {

                $msg[] = "Por favor digite um número válido!";
            }
        }break;
}

if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}