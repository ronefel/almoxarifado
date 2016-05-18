<?php

$nivel = 1;
include_once '../config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

require_once '../action/compraAction.php';
require_once '../action/estoquemovimentoAction.php';
require_once '../action/fornecedorAction.php';

$compra = new compraModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['compraid'])) {

    $compra->setCompraid($_POST['compraid']);
}

if (isset($_POST['fornecedorid'])) {

    $compra->setFornecedorid($_POST['fornecedorid']);
}

if (isset($_POST['compraemissao'])) {

    $compra->setCompraemissao($_POST['compraemissao']);
}

if (isset($_POST['compraaprovacao'])) {

    $compra->setCompraaprovacao($_POST['compraaprovacao']);
}

if (isset($_POST['comprarecebimento'])) {

    $compra->setCompraentrega($_POST['comprarecebimento']);
}

if (isset($_POST['comprareprovacao'])) {

    $compra->setComprareprovacao($_POST['comprareprovacao']);
}

if (isset($_POST['comprareprovacaotxt'])) {

    $compra->setComprareprovacaotxt($_POST['comprareprovacaotxt']);
}

switch ($control) {

    case 'view': {


            $datainicial = util::dateToUS(util::getData(-7));
            $datafinal = util::dateToUS(util::getData());

            $_POST['datainicial'] = util::dateToBR($datainicial);
            $_POST['datafinal'] = util::dateToBR($datafinal);

            $compras = new compraModel();
            $compras = compraAction::listCompra($datainicial, $datafinal);

            $fornecedores = array(new fornecedorModel());
            $fornecedores = fornecedorAction::listFornecedor();



            include_once '../table/compratable.php';
        }break;

    case 'novo': {

            if ($compra->getFornecedorid() == "") {

                $msg[] = "Tem que selecionar um fornecedor.<br/>";
            }

            if ($compra->getCompraemissao() == "") {

                $msg[] = "O campo 'Data' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                $compra->setComprasituacao(1);

                $result = compraAction::insertCompra($compra);

                if ($result) {

                    $msg[] = "sucesso=" . $result;
                } else {

                    $msg[] = "Ouve um erro na hora de cadastrar a compra.";
                }
            }
        }break;

    case 'editar': {

            $compradb = getCompra($compra);

            if ($compradb->getComprasituacao() > 1) {

                $msg[] = "Só pode editar a compra em aberta.<br/>";
            } else {

                if ($compra->getFornecedorid() == "") {

                    $msg[] = "Tem que selecionar um fornecedor.<br/>";
                }

                if ($compra->getCompraemissao() == "") {

                    $msg[] = "O campo 'Data' não pode ser vazio.<br/>";
                }

                if (!count($msg) > 0) {

                    $result = compraAction::updateCompra($compra);
                    if ($result) {

                        $msg[] = "sucesso";
                    } else {

                        $msg[] = "Ouve um erro na hora de atualizar a compra.";
                    }
                }
            }
        }break;

    case 'aprovar': {

            $compradb = getCompra($compra);

            if ($compradb->getComprasituacao() > 1) {

                $msg[] = "Só pode ser aprovado a compra em aberta.<br/>";
            } else {

                $itens = getItens($compra->getCompraid());
                if (count($itens) == 0) {

                    $msg[] = "Para ser aprovado o pedido de compra deve ter pelo menos um produto.";
                } else {

                    if ($compra->getCompraaprovacao() == "") {

                        $msg[] = "O campo 'Data Aprovação' não pode ser vazio.<br/>";
                    } else {

                        $compra->setComprasituacao(2);

                        $result = compraAction::updateCompra($compra);
                        if ($result) {

                            $msg[] = "sucesso";
                        } else {

                            $msg[] = "Ouve um erro na hora de aprovar a compra.";
                        }
                    }
                }
            }
        }break;

    case 'receber': {

            $compradb = getCompra($compra);

            if ($compradb->getComprasituacao() > 2) {

                $msg[] = "Só pode ser recebida a compra em aberta ou aprovada.<br/>";
            } else {

                $itens = array(new estoquemovimentoModel());
                $itens = getItens($compra->getCompraid());
                if (count($itens) == 0) {

                    $msg[] = "Para ser recebido o pedido de compra deve ter pelo menos um produto.";
                } else {

                    if ($compra->getCompraentrega() == "") {

                        $msg[] = "O campo 'Data Recebimento' não pode ser vazio.<br/>";
                    } else {

                        if ($compra->getCompraaprovacao() == "") {

                            $compra->setCompraaprovacao($compra->getCompraentrega());
                        }
                    }

                    if (!count($msg) > 0) {

                        $compra->setComprasituacao(3);

                        $result = compraAction::updateCompra($compra);
                        if ($result) {

                            $resultitens = 0;
                            for ($i = 0; $i < count($itens); $i++) {

                                $estoquemovimento = new estoquemovimentoModel();
                                $estoquemovimento->setEstoquemovimentoid($itens[$i]->getEstoquemovimentoid());
                                $estoquemovimento->setEstoquemovimentodata($compra->getCompraentrega());
                                $estoquemovimento->setOperacao(1); //operação de entrada no estoque

                                $resultitens = estoquemovimentoAction::updateEstoquemovimento($estoquemovimento);

                                if (!$resultitens) {
                                    break;
                                }
                            }
                            if ($resultitens) {
                                $msg[] = "sucesso";
                            } else {
                                $msg[] = "Ouve um erro na hora de dar entrada no estoque.";
                            }
                        } else {

                            $msg[] = "Ouve um erro na hora de receber a compra.";
                        }
                    }
                }
            }
        }break;

    case 'reprovar': {

            $compradb = getCompra($compra);

            if ($compradb->getComprasituacao() > 2) {

                $msg[] = "Só pode ser reprovada a compra em aberta ou aprovada.<br/>";
            } else {

                if ($compra->getComprareprovacao() == "") {

                    $compra->setComprareprovacao(util::getData());
                }

                if (!count($msg) > 0) {

                    $compra->setComprasituacao(4);

                    $result = compraAction::updateCompra($compra);
                    if ($result) {

                        $msg[] = "sucesso";
                    } else {

                        $msg[] = "Ouve um erro na hora de reprovar a compra.";
                    }
                }
            }
        }break;

    case 'excluir': {

            $result = compraAction::deleteCompra($compra);

            if ($result) {

                $msg[] = 'sucesso';
            } else {

                $msg[] = 'Ouve um erro na hora de excluir a compra.';
            }
        }break;

    case 'search': {

            $filters = new stdClass();

            if (isset($_POST['situacao'])) {
                $situacao = $_POST['situacao'];
                for ($i = 0; $i < count($situacao); $i++) {

                    $filters->situacao[$i] = $situacao[$i];
                }
            } else {
                $filters->situacao = "";
            }
            if (isset($_POST['compraid'])) {
                $filters->compraid = $_POST['compraid'];
            } else {
                $filters->compraid = "";
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
            if (isset($_POST['fornecedorid'])) {
                $filters->fornecedorid = $_POST['fornecedorid'];
            } else {
                $filters->fornecedorid = "";
            }

            $compras = new compraModel();
            $compras = compraAction::searchCompra($filters);

            $fornecedores = array(new fornecedorModel());
            $fornecedores = fornecedorAction::listFornecedor();

            include_once '../table/compratable.php';
        }break;

    case 'relatorio': {

            $filters = new stdClass();

            if (isset($_POST['situacao'])) {
                $situacao = $_POST['situacao'];
                for ($i = 0; $i < count($situacao); $i++) {

                    $filters->situacao[$i] = $situacao[$i];
                }
            } else {
                $filters->situacao = "";
            }
            if (isset($_POST['compraid'])) {
                $filters->compraid = $_POST['compraid'];
            } else {
                $filters->compraid = "";
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
            if (isset($_POST['fornecedorid'])) {
                $filters->fornecedorid = $_POST['fornecedorid'];
            } else {
                $filters->fornecedorid = "";
            }

            $compras = new compraModel();
            $compras = compraAction::searchCompra($filters);

            if (count($compras) > 0) {
                $estoquetotal = 0.0;

                ob_start();
                include_once "../report/relatorio/compra.php";
                $relatorio = ob_get_clean();

                $msg[] = util::gerarPDF($relatorio, "Relatório de Compras");
            } else {
                $msg[] = 'erro=Nenhuma compra com os dados informados.';
            }
        }break;
}


if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}

function getItens($compraid) {

    $estoquemovimento = new estoquemovimentoModel();
    $estoquemovimento->setCompraid($compraid);

    return estoquemovimentoAction::listEstoquemovimentocompra($estoquemovimento);
}

function getCompra($compra) {

    if ($compra->getCompraid() == "") {

        echo "Erro: O sistema não conseguiu identificar a compra.";
        exit();
    } else {

        $compradb = compraAction::getCompra($compra);

        return $compradb;
    }
}
