<?php

$nivel = 1;
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/produtosubgrupoAction.php';
$produtosubgrupo = new produtosubgrupoModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['produtosubgrupoid']) && !empty($_POST['produtosubgrupoid'])) {
    $produtosubgrupo->setProdutosubgrupoid($_POST['produtosubgrupoid']);
}

if (isset($_POST['produtogrupoid'])) {
    $produtosubgrupo->setProdutogrupoid($_POST['produtogrupoid']);
}

if (isset($_POST['produtosubgruponome'])) {
    $produtosubgrupo->setNome($_POST['produtosubgruponome']);
}

switch ($control) {

    case 'view': {

            $produtosubgrupos = new produtosubgrupoModel();
            $produtosubgrupos = produtosubgrupoAction::listProdutosubgrupo();

            include_once '../table/produtosubgrupotable.php';
        }break;

    case 'novo': {

            if ($produtosubgrupo->getProdutogrupoid() == "") {
                $msg[] = "Tem que selecionar um 'Grupo de Produtos'.<br/>";
            }

            if ($produtosubgrupo->getNome() == "") {
                $msg[] = "O Campo 'Descrição do Subgrupo de Produtos' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {
                if (produtosubgrupoAction::isExists($produtosubgrupo)) {
                    $msg[] = "Este Subgrupo de Produto já está cadastrado neste Grupo de Produto.<br/>";
                } else {
                    $result = produtosubgrupoAction::insertProdutosubgrupo($produtosubgrupo);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de cadastrar o Subgrupo de Produto.';
                    }
                }
            }
        }break;

    case 'editar': {

            if ($produtosubgrupo->getProdutogrupoid() == "") {
                $msg[] = "Tem que selecionar um 'Grupo de Produtos'.<br/>";
            }

            if ($produtosubgrupo->getNome() == "") {
                $msg[] = "O Campo 'Descrição do Subgrupo de Produtos' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {
                if (produtosubgrupoAction::isExists($produtosubgrupo)) {
                    $msg[] = "Este Subgrupo de Produto já está cadastrado neste Grupo de Produto.<br/>";
                } else {
                    $result = produtosubgrupoAction::updateProdutosubgrupo($produtosubgrupo);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de atualizar o Subgrupo de Produto.';
                    }
                }
            }
        }break;

    case 'excluir': {

            $result = produtosubgrupoAction::deleteProdutosubgrupo($produtosubgrupo);
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