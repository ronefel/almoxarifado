<?php

$nivel = 1;
include_once '../config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/produtogrupoAction.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

$produtogrupo = new produtogrupoModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['produtogrupoid'])) {
    $produtogrupo->setProdutogrupoid($_POST['produtogrupoid']);
}

if (isset($_POST['produtogruponome'])) {
    $produtogrupo->setNome($_POST['produtogruponome']);
}

switch ($control) {

    case 'view': {

            $produtogrupos = new produtogrupoModel();
            $produtogrupos = produtogrupoAction::listProdutogrupo();

            include_once '../table/produtogrupotable.php';
        }break;

    case 'novo': {

            if ($produtogrupo->getNome() == "") {

                $msg[] = "O Campo 'Nome do Grupo' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                if (produtogrupoAction::isExists($produtogrupo)) {

                    $msg[] = "Este Grupo de Produto já está cadastrado.<br/>";
                } else {

                    $result = produtogrupoAction::insertProdutogrupo($produtogrupo);
                    if ($result) {

                        $msg[] = 'sucesso';
                    } else {

                        $msg[] = 'Ouve um erro na hora de cadastrar o Grupo de Produto.';
                    }
                }
            }
        }break;

    case 'editar': {

            if ($produtogrupo->getNome() == "") {

                $msg[] = "O Campo 'Nome do Grupo' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {
                if (produtogrupoAction::isExists($produtogrupo)) {

                    $msg[] = "Este Grupo de Produto já está cadastrado.<br/>";
                } else {

                    $result = produtogrupoAction::updateProdutogrupo($produtogrupo);
                    if ($result) {

                        $msg[] = 'sucesso';
                    } else {

                        $msg[] = 'Ouve um erro na hora de atualizar o Grupo de Produto.';
                    }
                }
            }
        }break;

    case 'excluir': {

            $result = produtogrupoAction::deleteProdutogrupo($produtogrupo);
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