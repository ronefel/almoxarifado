<?php

$nivel = 1;
include_once '../config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

require_once '../action/localAction.php';
$local = new localModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['localid'])) {
    $local->setLocalid($_POST['localid']);
}

if (isset($_POST['localnome'])) {
    $local->setLocalnome($_POST['localnome']);
}

switch ($control) {

    case 'view': {

            $local = new localModel();
            $local = localAction::listLocal();

            include_once '../table/localtable.php';
        }break;

    case 'novo': {

            if ($local->getLocalnome() == "") {
                $msg[] = "O Campo 'Nome do Local' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                if (localAction::isExists($local)) {
                    $erro[] = "Este Local já está cadastrado.<br/>";
                } else {
                    $result = localAction::insertLocal($local);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de cadastrar o Local.';
                    }
                }
            }
        }break;

    case 'editar': {

            if ($local->getLocalnome() == "") {
                $msg[] = "O Campo 'Nome do Local' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                if (localAction::isExists($local)) {
                    $erro[] = "Este Local já está cadastrado.<br/>";
                } else {
                    $result = localAction::updateLocal($local);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de atualizar o Local.';
                    }
                }
            }
        }break;

    case 'excluir': {

            $result = localAction::deleteLocal($local);
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
