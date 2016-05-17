<?php

$nivel = 1;
include_once '../config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/cidadeAction.php';
$cidade = new cidadeModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['cidadeid'])) {
    $cidade->setCidadeid($_POST['cidadeid']);
}

if (isset($_POST['cidadenome'])) {
    $cidade->setNome($_POST['cidadenome']);
}

if (isset($_POST['cidadeuf'])) {
    $cidade->setUf($_POST['cidadeuf']);
}

if (isset($_POST['cidadecep'])) {
    $cidade->setCep($_POST['cidadecep']);
}

switch ($control) {

    case 'view': {

            $cidade = new cidadeModel();
            $cidade = cidadeAction::listCidade();

            include_once '../table/cidadetable.php';
        }break;

    case 'novo': {

            if ($cidade->getNome() == "") {
                $msg[] = "O Campo 'Nome da Cidade' não pode ser vazio.<br/>";
            }

            if ($cidade->getUf() == "") {
                $msg[] = "O Campo 'UF' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                if (cidadeAction::isExists($cidade)) {
                    $msg[] = "Este CEP ou Nome de cidade já está cadastrado.<br/>";
                } else {
                    $result = cidadeAction::insertCidade($cidade);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de cadastrar a cidade.';
                    }
                }
            }
        }break;

    case 'editar': {

            if ($cidade->getNome() == "") {
                $msg[] = "O Campo 'Nome da Cidade' não pode ser vazio.<br/>";
            }

            if ($cidade->getUf() == "") {
                $msg[] = "O Campo 'UF' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                if (cidadeAction::isExists($cidade)) {
                    $msg[] = "Este CEP ou Nome de cidade já está cadastrado.<br/>";
                } else {
                    $result = cidadeAction::updateCidade($cidade);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de atualizar a cidade.';
                    }
                }
            }
        }break;

    case 'excluir': {

            $result = cidadeAction::deleteCidade($cidade);
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