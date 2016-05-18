<?php
header ('Content-type: text/html; charset=UTF-8',true);
$nivel = 1;
include_once '../config.php';

require_once '../action/produtoAction.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

$produto = new produtoModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['produtoid'])) {
    $produto->setProdutoid($_POST['produtoid']);
}

if (isset($_POST['produtonome'])) {
    $produto->setProdutonome($_POST['produtonome']);
}

if (isset($_POST['produtound'])) {
    $produto->setUnd($_POST['produtound']);
}

if (isset($_POST['produtogrupoid'])) {
    $produto->setProdutogrupoid($_POST['produtogrupoid']);
}

if (isset($_POST['produtosubgrupoid'])) {
    $produto->setProdutosubgrupoid($_POST['produtosubgrupoid']);
}

if (isset($_POST['produtocustomedio'])) {
    $produto->setCustomedio($_POST['produtocustomedio']);
}

if (isset($_POST['produtoestoqueminimo'])) {
    $produto->setEstoqueminimo($_POST['produtoestoqueminimo']);
}

if (isset($_POST['produtoestoquemaximo'])) {
    $produto->setEstoquemaximo($_POST['produtoestoquemaximo']);
}
if (isset($_POST['produtocodigobarras'])) {
    $produto->setCodigobarras($_POST['produtocodigobarras']);
}

if (isset($_POST['produtoativo'])) {
    $produto->setAtivo($_POST['produtoativo']);
}

if (isset($_POST['produtoobservacao'])) {
    $produto->setObservacoes($_POST['produtoobservacao']);
}

switch ($control) {

    case 'view': {

            $produtos = new produtoModel();
            $produtos = produtoAction::listProduto();

            include_once '../table/produtotable.php';
        }break;

    case 'novo': {

            if ($produto->getProdutonome() == "") {

                $msg[] = "O Campo 'Descrição do Produto' não pode ser vazio.<br/>";
            }
            if ($produto->getUnd() == "") {

                $msg[] = "O Campo 'Unidade' não pode ser vazio.<br/>";
            }
            if ($produto->getProdutogrupoid() == "") {

                $msg[] = "Tem que selecionar um 'Grupo de Produtos'.<br/>";
            }
            if ($produto->getProdutosubgrupoid() == "") {

                $msg[] = "Tem que selecionar um 'Subgrupo de Produtos'.<br/>";
            }

            if (!count($msg) > 0) {

                $result = produtoAction::insertProduto($produto);
                if ($result) {
                    $msg[] = 'sucesso';
                } else {
                    $msg[] = 'Ouve um erro na hora de cadastrar o produto.';
                }
            }
        }break;

    case 'editar': {

            if ($produto->getProdutonome() == "") {

                $msg[] = "O Campo 'Descrição do Produto' não pode ser vazio.<br/>";
            }
            if ($produto->getUnd() == "") {

                $msg[] = "O Campo 'Unidade' não pode ser vazio.<br/>";
            }
            if ($produto->getProdutogrupoid() == "") {

                $msg[] = "Tem que selecionar um 'Grupo de Produtos'.<br/>";
            }
            if ($produto->getProdutosubgrupoid() == "") {

                $msg[] = "Tem que selecionar um 'Subgrupo de Produtos'.<br/>";
            }

            if (!count($msg) > 0) {

                $result = produtoAction::updateProduto($produto);
                if ($result) {
                    $msg[] = 'sucesso';
                } else {
                    $msg[] = 'Ouve um erro na hora de atualizar o produto.';
                }
            }
        }break;

    case 'excluir': {

            $result = produtoAction::deleteProduto($produto);

            if ($result) {

                $msg[] = 'sucesso';
            } else {

                $msg[] = $result;
            }
        }break;

    case 'relatorio': {

            $produtos = new produtoModel();
            $produtos = produtoAction::listProduto();

            if (count($produtos) > 0) {
                $estoquetotal = 0.0;

                ob_start();
                include_once "../report/relatorio/produto.php";
                $relatorio = ob_get_clean();

                $msg[] = util::gerarPDF($relatorio, "Relatório de Produtos em Estoque");
            } else {
                $msg[] = 'erro=Nenhum produto cadastrado no estoque.';
            }
        }break;
}
if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}