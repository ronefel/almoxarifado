<?php
include_once '../config.php';
require_once '../action/estoquemovimentoAction.php';
require_once '../action/compraAction.php';
require_once '../action/fornecedorAction.php';
require_once '../action/usuarioAction.php';
require_once '../action/produtoAction.php';
$estoquemovimento = new estoquemovimentoModel();
$produto = new produtoModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['estoquemovimentoid'])) {

    $estoquemovimento->setEstoquemovimentoid($_POST['estoquemovimentoid']);
}

if (isset($_POST['estoquemovimentocompraid'])) {

    $estoquemovimento->setCompraid($_POST['estoquemovimentocompraid']);
}
if (isset($_POST['estoquemovimentorequisicaoid'])) {

    $estoquemovimento->setRequisicaoid($_POST['estoquemovimentorequisicaoid']);
}

if (isset($_POST['produtoid'])) {

    $estoquemovimento->setEM_produtoid($_POST['produtoid']);
}

if (isset($_POST['estoquemovimentoquantidade'])) {

    $estoquemovimento->setQuantidade($_POST['estoquemovimentoquantidade']);
}

if (isset($_POST['estoquemovimentovalorunitario'])) {

    $estoquemovimento->setValorunitario($_POST['estoquemovimentovalorunitario']);
}

if (isset($_POST['estoquemovimentodata'])) {

    $estoquemovimento->setEstoquemovimentodata($_POST['estoquemovimentodata']);
}

if (isset($_POST['estoquemovimentooperacao'])) {

    $estoquemovimento->setOperacao($_POST['estoquemovimentooperacao']);
}

switch ($control) {

    case 'view': {

            $datainicial = util::dateToUS(util::getData(-7));
            $datafinal = util::dateToUS(util::getData());

            $_POST['datainicial'] = util::dateToBR($datainicial);
            $_POST['datafinal'] = util::dateToBR($datafinal);

            $fornecedores = fornecedorAction::listFornecedor();
            $produtos = produtoAction::listProduto();
            $requisitantes = usuarioAction::listRequisitante();
            $estoquemovimento = estoquemovimentoAction::listEstoquemovimento($datainicial, $datafinal);
            include_once '../table/estoquemovimentotable.php';
        }break;

    case 'novo': {

            if ($estoquemovimento->getEM_produtoid() == "") {

                $msg[] = "Tem que selecionar um 'Produto'.<br/>";
            }

            if ($estoquemovimento->getQuantidade() == "") {

                $msg[] = "O Campo 'Quantidade' não pode ser vazio.<br/>";
            }

            if ($estoquemovimento->getEstoquemovimentodata() != "") {

                if (util::dataValida($estoquemovimento->getEstoquemovimentodata())) {

                    $estoquemovimento->setEstoquemovimentodata(util::dateToUS($estoquemovimento->getEstoquemovimentodata()));
                } else {

                    $msg[] = "Tem que informar uma data de cadastro válida.<br/>";
                }
            } else {

                $estoquemovimento->setEstoquemovimentodata(date("Y-m-d"));
            }

            if ($estoquemovimento->getOperacao() == "") {

                $msg[] = "Tem que selecionar uma 'Operação'.<br/>";
            }
            if ($estoquemovimento->getOperacao() == 2) {

                $produto->setProdutoid($estoquemovimento->getEM_produtoid());
                $produto = produtoAction::getProduto($produto);
                $estoquefuturo = $produto->getEstoqueatual() - $estoquemovimento->getQuantidade();
                if ($estoquefuturo < 0) {

                    $msg[] = "Impossível tirar mais do que tem em estoque. Por favor verifique o estoque atual desse produto.<br/>";
                }
            }

            if (!count($msg) > 0) {

                $result = estoquemovimentoAction::insertEstoquemovimento($estoquemovimento);
                if ($result) {

                    $msg[] = 'sucesso';
                } else {

                    $msg[] = 'Ouve um erro na hora de salvar o movimento do estoque.<br/>';
                }
            }
        }break;

    case 'editar': {

            if ($estoquemovimento->getEM_produtoid() == "") {

                $msg[] = "Tem que selecionar um 'Produto'.<br/>";
            }

            if ($estoquemovimento->getQuantidade() == "") {

                $msg[] = "O Campo 'Quantidade' não pode ser vazio.<br/>";
            }

            if ($estoquemovimento->getEstoquemovimentodata() != "") {

                if (util::dataValida($estoquemovimento->getEstoquemovimentodata())) {

                    $estoquemovimento->setEstoquemovimentodata(util::dateToUS($estoquemovimento->getEstoquemovimentodata()));
                } else {

                    $msg[] = "Tem que informar uma data de cadastro válida.<br/>";
                }
            } else {

                $estoquemovimento->setEstoquemovimentodata(date("Y-m-d"));
            }

            if ($estoquemovimento->getOperacao() == "") {

                $msg[] = "Tem que selecionar uma 'Operação'.<br/>";
            }
            if ($estoquemovimento->getOperacao() == 2) {

                $produto->setProdutoid($estoquemovimento->getEM_produtoid());
                $produto = produtoAction::getProduto($produto);
                $estoquefuturo = $produto->getEstoqueatual() - $estoquemovimento->getQuantidade();
                if ($estoquefuturo < 0) {

                    $msg[] = "Impossível tirar mais do que tem em estoque. Por favor verifique o estoque atual desse produto.<br/>";
                }
            }

            if (!count($msg) > 0) {

                $result = estoquemovimentoAction::updateEstoquemovimento($estoquemovimento);
                if ($result) {

                    $msg[] = 'sucesso';
                } else {

                    $msg[] = 'Ouve um erro na hora de editar o movimento do estoque.<br/>';
                }
            }
        }break;

    case 'incluiritem': {

            if ($estoquemovimento->getCompraid() == "" && $estoquemovimento->getRequisicaoid() == "") {

                $msg[] = "Erro: não foi possível incluir o item neste movimento. Atualize o navegador e tente novamente.<br/>";
            }
            if ($estoquemovimento->getEM_produtoid() == "") {

                $msg[] = "Tem que selecionar um 'Produto'.<br/>";
            }
            if ($estoquemovimento->getQuantidade() == "") {

                $msg[] = "O Campo 'Quantidade' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                $estoquemovimentodb = estoquemovimentoExist($estoquemovimento);

                if ($estoquemovimentodb->getEstoquemovimentoid()) {
                    $msg[] = "Este produto já está adicionado. Se quer editá-lo exclua da lista e adicione novamente.";
                } else {

                    $estoquemovimento->setOperacao(0); //seta a operação: em aberto
                    $result = estoquemovimentoAction::insertEstoquemovimento($estoquemovimento);
                    if ($result) {

                        $msg[] = 'sucesso';
                    } else {

                        $msg[] = 'Ouve um erro na hora de incluir o item.<br/>';
                    }
                }
            }
        }break;

    case 'excluir': {

            $result = estoquemovimentoAction::deleteEstoquemovimento($estoquemovimento);
            if ($result) {

                $msg[] = 'sucesso';
            } else {

                $msg[] = 'Ouve um erro na hora de excluir o produto.<br/>';
            }
        }break;

    case 'search': {

            $filters = new stdClass();

            if (isset($_POST['operacao'])) {
                $operacao = $_POST['operacao'];
                for ($i = 0; $i < count($operacao); $i++) {

                    $filters->operacao[$i] = $operacao[$i];
                }
            } else {
                $filters->operacao = "";
            }
            if (isset($_POST['datainicial'])) {
                $filters->datainicial = $_POST['datainicial'];
            } else {
                $filters->datainicial = "";
            }
            if (isset($_POST['datafinal'])) {
                $filters->datafinal = $_POST['datafinal'];
            } else {
                $filters->datafinal = "";
            }
            if (isset($_POST['usuarioid'])) {
                $filters->usuarioid = $_POST['usuarioid'];
            } else {
                $filters->usuarioid = "";
            }
            if (isset($_POST['fornecedorid'])) {
                $filters->fornecedorid = $_POST['fornecedorid'];
            } else {
                $filters->fornecedorid = "";
            }
            if (isset($_POST['produtoid'])) {
                $filters->produtoid = $_POST['produtoid'];
            } else {
                $filters->produtoid = "";
            }

            $estoquemovimento = new estoquemovimentoModel();
            $estoquemovimento = estoquemovimentoAction::searchEstoquemovimento($filters);

            $fornecedores = fornecedorAction::listFornecedor();
            $produtos = produtoAction::listProduto();
            $requisitantes = usuarioAction::listRequisitante();

            include_once '../table/estoquemovimentotable.php';
        }break;

    case 'relatorio': {

            $filters = new stdClass();

            if (isset($_POST['operacao'])) {
                $operacao = $_POST['operacao'];
                for ($i = 0; $i < count($operacao); $i++) {

                    $filters->operacao[$i] = $operacao[$i];
                }
            } else {
                $filters->operacao = "";
            }
            if (isset($_POST['datainicial'])) {
                $filters->datainicial = $_POST['datainicial'];
            } else {
                $filters->datainicial = "";
            }
            if (isset($_POST['datafinal'])) {
                $filters->datafinal = $_POST['datafinal'];
            } else {
                $filters->datafinal = "";
            }
            if (isset($_POST['usuarioid'])) {
                $filters->usuarioid = $_POST['usuarioid'];
            } else {
                $filters->usuarioid = "";
            }
            if (isset($_POST['fornecedorid'])) {
                $filters->fornecedorid = $_POST['fornecedorid'];
            } else {
                $filters->fornecedorid = "";
            }
            if (isset($_POST['produtoid'])) {
                $filters->produtoid = $_POST['produtoid'];
            } else {
                $filters->produtoid = "";
            }

            $estoquemovimento = new estoquemovimentoModel();
            $estoquemovimento = estoquemovimentoAction::searchEstoquemovimento($filters);
            $estoquemovimentototal = new estoquemovimentoModel();
            $estoquemovimentototal = estoquemovimentoAction::searchEstoquemovimentoTotal($filters);

            $fornecedores = fornecedorAction::listFornecedor();
            $produtos = produtoAction::listProduto();
            $requisitantes = usuarioAction::listRequisitante();

            if (count($estoquemovimento) > 0) {
                $estoquetotal = 0.0;

                ob_start();
                include_once "../report/relatorio/estoquemovimento.php";
                $relatorio = ob_get_clean();

                $msg[] = util::gerarPDF($relatorio, "Relatório de Movimento no Estoque");
            } else {
                $msg[] = 'erro=Nenhum movimento de estoque com os dados informados.';
            }
        }break;
}

if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}

function getEstoquemovimento($estoquemovimento) {

    if ($estoquemovimento->getEstoquemovimentoid() == "") {

        echo "Erro: O sistema não conseguiu identificar o movimento do estoque.";
        exit();
    } else {

        $estoquemovimentodb = estoquemovimentoAction::getEstoquemovimento($estoquemovimento);

        return $estoquemovimentodb;
    }
}

function estoquemovimentoExist($estoquemovimento) {

    $estoquemovimentodb = "";

    if ($estoquemovimento->getCompraid() != "") {

        $estoquemovimentodb = estoquemovimentoAction::getEstoquemovimentocompra($estoquemovimento);
    } else
    if ($estoquemovimento->getRequisicaoid() != "") {
        $estoquemovimentodb = estoquemovimentoAction::getEstoquemovimentorequisicao($estoquemovimento);
    } else {
        echo "Erro: O sistema não conseguiu identificar o produto.";
        exit();
    }

    return $estoquemovimentodb;
}
