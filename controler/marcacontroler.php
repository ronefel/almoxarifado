<?php

$nivel = 1;
include_once '../config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

require_once '../action/marcaAction.php';
$marca = new marcaModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['marcaid'])) {
    $marca->setMarcaid($_POST['marcaid']);
}

if (isset($_POST['marcanome'])) {
    $marca->setMarcanome($_POST['marcanome']);
}

switch ($control) {

    case 'view': {

            $marca = new marcaModel();
            $marca = marcaAction::listMarca();

            include_once '../table/marcatable.php';
        }break;

    case 'novo': {

            if ($marca->getMarcanome() == "") {
                $msg[] = "O Campo 'Nome do Marca' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                if (marcaAction::isExists($marca)) {
                    $msg[] = "Este Marca já está cadastrado.<br/>";
                } else {
                    $result = marcaAction::insertMarca($marca);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de cadastrar o Marca.';
                    }
                }
            }
        }break;

    case 'editar': {

            if ($marca->getMarcanome() == "") {
                $msg[] = "O Campo 'Nome do Marca' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                if (marcaAction::isExists($marca)) {
                    $msg[] = "Este Marca já está cadastrado.<br/>";
                } else {
                    $result = marcaAction::updateMarca($marca);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de atualizar o Marca.';
                    }
                }
            }
        }break;

    case 'excluir': {

            $result = marcaAction::deleteMarca($marca);
            if ($result == 'sucesso') {
                $msg[] = 'sucesso';
            } else {
                $msg[] = $result;
            }
        }break;
}

if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}
