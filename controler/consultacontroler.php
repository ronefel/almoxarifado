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
                    
                    $JSON = "{\"Patrimônio\":\"{$patrimonio->getPatrimonioid()}\","
                            . "\"Produto\":\"{$patrimonio->getProdutonome()}\","
                            . "\"Local\":\"{$patrimonio->getLocalnome()}\","
                            . "\"Departamento\":\"{$patrimonio->getDepartamentonome()}\","
                            . "\"Estado de Conservação\":\"{$patrimonio->getEstadoconservacao()}\"}";
                    
                    $msg[]= $JSON;
                } else {

                    $msg[] = "O campo 'patrimônio' não pode ser vazio!";
                }
        }break;
    
}

if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}