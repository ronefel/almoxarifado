<?php

$nivel = 1;
include_once '../config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

require_once '../action/categoriaAction.php';
$categoria = new categoriaModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['categoriaid'])) {
    $categoria->setCategoriaid($_POST['categoriaid']);
}

if (isset($_POST['categorianome'])) {
    $categoria->setCategorianome($_POST['categorianome']);
}

switch ($control) {

    case 'view': {

            $categoria = new categoriaModel();
            $categoria = categoriaAction::listCategoria();

            include_once '../table/categoriatable.php';
        }break;

    case 'novo': {

            if ($categoria->getCategorianome() == "") {
                $msg[] = "O Campo 'Nome do Categoria' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                if (categoriaAction::isExists($categoria)) {
                    $msg[] = "Este Categoria já está cadastrado.<br/>";
                } else {
                    $result = categoriaAction::insertCategoria($categoria);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de cadastrar o Categoria.';
                    }
                }
            }
        }break;

    case 'editar': {

            if ($categoria->getCategorianome() == "") {
                $msg[] = "O Campo 'Nome do Categoria' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                if (categoriaAction::isExists($categoria)) {
                    $msg[] = "Este Categoria já está cadastrado.<br/>";
                } else {
                    $result = categoriaAction::updateCategoria($categoria);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de atualizar o Categoria.';
                    }
                }
            }
        }break;

    case 'excluir': {

            $result = categoriaAction::deleteCategoria($categoria);
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
