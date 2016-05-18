<?php

$nivel = 1;
include_once '../config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

require_once '../action/requisicaoAction.php';
require_once '../action/estoquemovimentoAction.php';
require_once '../action/usuarioAction.php';

$requisicao = new requisicaoModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['requisicaoid'])) {

    $requisicao->setRequisicaoid($_POST['requisicaoid']);
}

if (isset($_POST['usuarioid'])) {

    $requisicao->setUsuarioid($_POST['usuarioid']);
}

if (isset($_POST['requisicaoemissao'])) {

    $requisicao->setRequisicaoemissao($_POST['requisicaoemissao']);
}

if (isset($_POST['requisicaoaprovacao'])) {

    $requisicao->setRequisicaoaprovacao($_POST['requisicaoaprovacao']);
}

if (isset($_POST['requisicaoentrega'])) {

    $requisicao->setRequisicaoentrega($_POST['requisicaoentrega']);
}

if (isset($_POST['requisicaoreprovacao'])) {

    $requisicao->setRequisicaoreprovacao($_POST['requisicaoreprovacao']);
}

if (isset($_POST['requisicaoreprovacaotxt'])) {

    $requisicao->setRequisicaoreprovacaotxt($_POST['requisicaoreprovacaotxt']);
}

switch ($control) {

    case 'view': {


            $datainicial = util::dateToUS(util::getData(-7));
            $datafinal = util::dateToUS(util::getData());

            $_POST['datainicial'] = util::dateToBR($datainicial);
            $_POST['datafinal'] = util::dateToBR($datafinal);

            $requisicoes = new requisicaoModel();
            $requisicoes = requisicaoAction::listRequisicao($datainicial, $datafinal);

            $usuarios = array(new usuarioModel());
            $usuarios = usuarioAction::listUsuario();



            include_once '../table/requisicaotable.php';
        }break;

    case 'novo': {

            if ($requisicao->getUsuarioid() == "") {

                $msg[] = "Tem que selecionar um usuario.<br/>";
            }

            if ($requisicao->getRequisicaoemissao() == "") {

                $msg[] = "O campo 'Data' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                $requisicao->setRequisicaosituacao(1);

                $result = requisicaoAction::insertRequisicao($requisicao);

                if ($result) {

                    $msg[] = "sucesso=" . $result;
                } else {

                    $msg[] = "Ouve um erro na hora de cadastrar a requisicao.";
                }
            }
        }break;

    case 'editar': {

            $requisicaodb = getRequisicao($requisicao);

            if ($requisicaodb->getRequisicaosituacao() > 1) {

                $msg[] = "Só pode editar a requisicao em aberta.<br/>";
            } else {

                if ($requisicao->getUsuarioid() == "") {

                    $msg[] = "Tem que selecionar um usuario.<br/>";
                }

                if ($requisicao->getRequisicaoemissao() == "") {

                    $msg[] = "O campo 'Data' não pode ser vazio.<br/>";
                }

                if (!count($msg) > 0) {

                    $result = requisicaoAction::updateRequisicao($requisicao);
                    if ($result) {

                        $msg[] = "sucesso";
                    } else {

                        $msg[] = "Ouve um erro na hora de atualizar a requisicao.";
                    }
                }
            }
        }break;

    case 'aprovar': {

            $requisicaodb = getRequisicao($requisicao);

            if ($requisicaodb->getRequisicaosituacao() > 1) {

                $msg[] = "Só pode ser aprovado a requisicao em aberta.<br/>";
            } else {

                $itens = getItens($requisicao->getRequisicaoid());
                if (count($itens) == 0) {

                    $msg[] = "Para ser aprovado o pedido de requisicao deve ter pelo menos um produto.";
                } else {

                    if ($requisicao->getRequisicaoaprovacao() == "") {

                        $msg[] = "O campo 'Data Aprovação' não pode ser vazio.<br/>";
                    } else {

                        $requisicao->setRequisicaosituacao(2);

                        $result = requisicaoAction::updateRequisicao($requisicao);
                        if ($result) {

                            $msg[] = "sucesso";
                        } else {

                            $msg[] = "Ouve um erro na hora de aprovar a requisicao.";
                        }
                    }
                }
            }
        }break;

    case 'entregar': {

            $requisicaodb = getRequisicao($requisicao);

            if ($requisicaodb->getRequisicaosituacao() > 2) {

                $msg[] = "Só pode ser recebida a requisicao em aberta ou aprovada.<br/>";
            } else {

                $itens = array(new estoquemovimentoModel());
                $itens = getItens($requisicao->getRequisicaoid());
                if (count($itens) == 0) {

                    $msg[] = "Para ser recebido o pedido de requisicao deve ter pelo menos um produto.";
                } else {

                    if ($requisicao->getRequisicaoentrega() == "") {

                        $msg[] = "O campo 'Data da Entrega' não pode ser vazio.<br/>";
                    } else {

                        if ($requisicao->getRequisicaoaprovacao() == "") {

                            $requisicao->setRequisicaoaprovacao($requisicao->getRequisicaoentrega());
                        }
                    }

                    if (!count($msg) > 0) {

                        $requisicao->setRequisicaosituacao(3);

                        $result = requisicaoAction::updateRequisicao($requisicao);
                        if ($result) {

                            $resultitens = 0;
                            for ($i = 0; $i < count($itens); $i++) {

                                $estoquemovimento = new estoquemovimentoModel();
                                $estoquemovimento->setEstoquemovimentoid($itens[$i]->getEstoquemovimentoid());
                                $estoquemovimento->setEstoquemovimentodata($requisicao->getRequisicaoentrega());
                                $estoquemovimento->setOperacao(2); //operação de saida no estoque

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

                            $msg[] = "Ouve um erro na hora de receber a requisicao.";
                        }
                    }
                }
            }
        }break;

    case 'reprovar': {

            $requisicaodb = getRequisicao($requisicao);

            if ($requisicaodb->getRequisicaosituacao() > 2) {

                $msg[] = "Só pode ser reprovada a requisicao em aberta ou aprovada.<br/>";
            } else {

                if ($requisicao->getRequisicaoreprovacao() == "") {

                    $requisicao->setRequisicaoreprovacao(util::getData());
                }

                if (!count($msg) > 0) {

                    $requisicao->setRequisicaosituacao(4);

                    $result = requisicaoAction::updateRequisicao($requisicao);
                    if ($result) {

                        $msg[] = "sucesso";
                    } else {

                        $msg[] = "Ouve um erro na hora de reprovar a requisicao.";
                    }
                }
            }
        }break;

    case 'excluir': {

            $result = requisicaoAction::deleteRequisicao($requisicao);

            if ($result) {

                $msg[] = 'sucesso';
            } else {

                $msg[] = 'Ouve um erro na hora de excluir a requisicao.';
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
            if (isset($_POST['requisicaoid'])) {
                $filters->requisicaoid = $_POST['requisicaoid'];
            } else {
                $filters->requisicaoid = "";
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

            $requisicoes = new requisicaoModel();
            $requisicoes = requisicaoAction::searchRequisicao($filters);

            $usuarios = array(new usuarioModel());
            $usuarios = usuarioAction::listUsuario();

            include_once '../table/requisicaotable.php';
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
            if (isset($_POST['requisicaoid'])) {
                $filters->requisicaoid = $_POST['requisicaoid'];
            } else {
                $filters->requisicaoid = "";
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

            $requisicoes = new requisicaoModel();
            $requisicoes = requisicaoAction::searchRequisicao($filters);

            if (count($requisicoes) > 0) {
                $titulo = "";
                $estoquetotal = 0.0;

                ob_start();
                if (count($requisicoes) == 1) {
                    $titulo = "Requisição";
                    $itens = getItens($requisicao->getRequisicaoid());
                    $requisicaoDados = getRequisicao($requisicao);
                    include_once "../report/relatorio/requisicaoDoc.php";
                } else {
                    $titulo = "Relatório de Requisições";
                    include_once "../report/relatorio/requisicao.php";
                }
                $relatorio = ob_get_clean();

                $msg[] = util::gerarPDF($relatorio, $titulo);
            } else {
                $msg[] = 'erro=Nenhuma requisição com os dados informados.';
            }
        }break;
}


if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}

function getItens($requisicaoid) {

    $estoquemovimento = new estoquemovimentoModel();
    $estoquemovimento->setRequisicaoid($requisicaoid);

    return estoquemovimentoAction::listEstoquemovimentorequisicao($estoquemovimento);
}

function getRequisicao($requisicao) {

    if ($requisicao->getRequisicaoid() == "") {

        echo "Erro: O sistema não conseguiu identificar a requisicao.";
        exit();
    } else {

        $requisicaodb = requisicaoAction::getRequisicao($requisicao);

        return $requisicaodb;
    }
}
