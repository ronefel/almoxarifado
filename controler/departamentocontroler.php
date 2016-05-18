<?php

$nivel = 1;
include_once '../config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

require_once '../action/departamentoAction.php';
$departamento = new departamentoModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['departamentoid'])) {
    $departamento->setDepartamentoid($_POST['departamentoid']);
}

if (isset($_POST['localid'])) {
    $departamento->setLocalid($_POST['localid']);
}

if (isset($_POST['departamentonome'])) {
    $departamento->setDepartamentonome($_POST['departamentonome']);
}

switch ($control) {

    case 'view': {

            $departamento = new departamentoModel();
            $departamento = departamentoAction::listDepartamento();

            include_once '../table/departamentotable.php';
        }break;

    case 'novo': {

            if ($departamento->getLocalid() == "") {

                $msg[] = "Tem que selecionar um 'Local'.<br/>";
            }
            if ($departamento->getDepartamentonome() == "") {

                $msg[] = "O Campo 'Nome do Departamento' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                if (departamentoAction::isExists($departamento)) {
                    $msg[] = "Este departamento já está cadastrado neste Local.<br/>";
                } else {
                    $result = departamentoAction::insertDepartamento($departamento);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de cadastrar o Departamento.';
                    }
                }
            }
        }break;

    case 'editar': {

            if ($departamento->getLocalid() == "") {

                $msg[] = "Tem que selecionar um 'Local'.<br/>";
            }
            if ($departamento->getDepartamentonome() == "") {

                $msg[] = "O Campo 'Nome do Departamento' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                if (departamentoAction::isExists($departamento)) {
                    $msg[] = "Este departamento já está cadastrado neste Local.<br/>";
                } else {
                    $result = departamentoAction::updateDepartamento($departamento);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de atualizar o Departamento.';
                    }
                }
            }
        }break;

    case 'excluir': {

            $result = departamentoAction::deleteDepartamento($departamento);
            if ($result == 'sucesso') {
                echo 'sucesso';
            } else {
                echo $result;
            }
        }
}

if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}