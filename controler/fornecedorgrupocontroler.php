<?php

$nivel = 1;
include_once '../config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

require_once '../action/fornecedorgrupoAction.php';
$fornecedorgrupo = new fornecedorgrupoModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['fornecedorgrupoid'])) {
    $fornecedorgrupo->setFornecedorgrupoid($_POST['fornecedorgrupoid']);
}

if (isset($_POST['fornecedorgruponome'])) {
    $fornecedorgrupo->setFornecedorgruponome($_POST['fornecedorgruponome']);
}

switch ($control) {

    case 'view': {

            $fornecedorgrupo = new fornecedorgrupoModel();
            $fornecedorgrupo = fornecedorgrupoAction::listFornecedorgrupo();

            include_once '../table/fornecedorgrupotable.php';
        }break;

    case 'novo': {

            if ($fornecedorgrupo->getFornecedorgruponome() == "") {
                $msg[] = "O Campo 'Nome do Grupo' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {
                if (fornecedorgrupoAction::isExists($fornecedorgrupo)) {
                    $msg[] = "Este Grupo de Fornecedor já está cadastrado.<br/>";
                } else {
                    $result = fornecedorgrupoAction::insertFornecedorgrupo($fornecedorgrupo);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de cadastrar o Grupo de Fornecedor.';
                    }
                }
            }
        }break;

    case 'editar': {

            if ($fornecedorgrupo->getFornecedorgruponome() == "") {
                $msg[] = "O Campo 'Nome do Grupo' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                if (fornecedorgrupoAction::isExists($fornecedorgrupo)) {
                    $msg[] = "Este Grupo de Fornecedor já está cadastrado.<br/>";
                } else {
                    $result = fornecedorgrupoAction::updateFornecedorgrupo($fornecedorgrupo);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de atualizar o Grupo de Fornecedor.';
                    }
                }
            }
        }break;

    case 'excluir': {

            $result = fornecedorgrupoAction::deleteFornecedorgrupo($fornecedorgrupo);
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
