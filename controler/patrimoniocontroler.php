<?php

header('Content-type: text/html; charset=UTF-8', true);
$nivel = 1;
include_once '../config.php';

require_once '../action/patrimonioAction.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

$patrimonio = new patrimonioModel();
$msg = array();
$control = "";
$patrimonioidlote = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['patrimonioid'])) {
    $patrimonio->setPatrimonioid($_POST['patrimonioid']);
}

if (isset($_POST['produtoid'])) {
    $patrimonio->setProdutoid($_POST['produtoid']);
}

//if (isset($_POST['serie'])) {
//    $patrimonio->setSerie($_POST['serie']);
//}
//
//if (isset($_POST['notafiscal'])) {
//    $patrimonio->setNotafiscal($_POST['notafiscal']);
//}

if (isset($_POST['valor'])) {
    $patrimonio->setValor($_POST['valor']);
}

if (isset($_POST['datacompra'])) {
    $patrimonio->setDatacompra($_POST['datacompra']);
}

if (isset($_POST['fimgarantia'])) {
    $patrimonio->setFimgarantia($_POST['fimgarantia']);
}

if (isset($_POST['dataimplantacao'])) {
    $patrimonio->setDataimplantacao($_POST['dataimplantacao']);
}

if (isset($_POST['estadoconservacao'])) {
    $patrimonio->setEstadoconservacao($_POST['estadoconservacao']);
}
if (isset($_POST['obs'])) {
    $patrimonio->setObs($_POST['obs']);
}

if (isset($_POST['patrimonioativo'])) {
    $patrimonio->setAtivo($_POST['patrimonioativo']);
}

if (isset($_POST['departamentoid'])) {
    $patrimonio->setDepartamentoid($_POST['departamentoid']);
}

if (isset($_POST['fornecedorid'])) {
    $patrimonio->setFornecedorid($_POST['fornecedorid']);
}
if (isset($_POST['lote'])) {
//verifica se o próximo caractere após a última virgual é um número
    if (isset($_POST['patrimoniosids'])) {

        $patrimoniosids = $_POST['patrimoniosids'];

        //converte string em array
        $patrimonioidlote = explode(',', $patrimoniosids);

        //remove valores repetidos do array
        $patrimonioidlote = array_unique($patrimonioidlote);

        //Remove Valores Nulos e Falsos
        $patrimonioidlote = array_filter($patrimonioidlote);

        //recria os indices
        $patrimonioidlote = array_values($patrimonioidlote);
    }
}

switch ($control) {

    case 'view': {

            $patrimonios = new patrimonioModel();
            $patrimonios = patrimonioAction::listPatrimonio();

            include_once '../table/patrimoniotable.php';
        }break;

    case 'novo': {

            //cadastra vários patrimônios de uma só vez
            if (isset($_POST['lote'])) {

                $est = "";
                for ($i = 0; $i < count($patrimonioidlote); $i++) {

                    //verifica se é um número válido
                    if (!is_numeric($patrimonioidlote[$i])) {

                        $est = $est . $patrimonioidlote[$i] . "; ";
                    } else {

                        $patrimoniodb = new patrimonioModel();
                        $patrimonio->setPatrimonioid($patrimonioidlote[$i]);
                        $patrimoniodb = patrimonioAction::getPatrimonio($patrimonio);

                        //verifica se o patrimônio informado já está cadastrado
                        if (strlen($patrimoniodb->getPatrimonioid()) > 0) {

                            $msg[0] = "Este(s) tombamento(s) já está(ão) cadastrado(s):<br/>";
                            $msg[] = $patrimonioidlote[$i] . ", ";
                        }
                    }
                }
                if (strlen($est) > 0) {

                    $msg[] = "Estes não são números válidos: " . $est;
                }
                if ($patrimonio->getProdutoid() == "") {

                    $msg[] = "Tem que selecionar um produto.<br/>";
                }

                if (!count($msg) > 0) {

                    for ($i = 0; $i < count($patrimonioidlote); $i++) {

                        $patrimonio->setPatrimonioid($patrimonioidlote[$i]);

                        $result = patrimonioAction::insertPatrimonio($patrimonio);
                        if ($result) {

                            $msg[0] = 'sucesso';
                        } else {

                            $msg[0] = 'Ouve um erro na hora de cadastrar este(s) patrimonio(s):';
                            $msg[] = $patrimonioidlote[$i] . "<br/>";
                        }
                    }
                }
            } else {

                $patrimoniodb = new patrimonioModel();
                if (strlen($patrimonio->getPatrimonioid()) > 0) {

                    $patrimoniodb = patrimonioAction::getPatrimonio($patrimonio);
                } else {

                    $msg[] = "O campo 'patrimônio' não pode ser vazio!";
                }

                if (strlen($patrimoniodb->getPatrimonioid()) > 0) {

                    $msg[] = "O tombamento informado já está cadastrado!";
                }

                if ($patrimonio->getProdutoid() == "") {

                    $msg[] = "Tem que selecionar um produto.<br/>";
                }

                if (!count($msg) > 0) {

                    $result = patrimonioAction::insertPatrimonio($patrimonio);
                    if ($result) {
                        $msg[] = 'sucesso';
                    } else {
                        $msg[] = 'Ouve um erro na hora de cadastrar o patrimonio.';
                    }
                }
            }
        }break;

    case 'editar': {

            if ($patrimonio->getProdutoid() == "") {

                $msg[] = "Tem que selecionar um produto.<br/>";
            }

            if (!count($msg) > 0) {

                $result = patrimonioAction::updatePatrimonio($patrimonio);
                if ($result) {
                    $msg[] = 'sucesso';
                } else {
                    $msg[] = 'Ouve um erro na hora de atualizar o patrimonio.';
                }
            }
        }break;

    case 'excluir': {

            $result = patrimonioAction::deletePatrimonio($patrimonio);

            if ($result) {

                $msg[] = 'sucesso';
            } else {

                $msg[] = $result;
            }
        }break;

    case 'relatorio': {

            $msg[] = 'Não implementado.';

//            $patrimonios = new patrimonioModel();
//            $patrimonios = patrimonioAction::listPatrimonio();
//
//            if (count($patrimonios) > 0) {
//                $estoquetotal = 0.0;
//
//                ob_start();
//                include_once "../report/relatorio/patrimonio.php";
//                $relatorio = ob_get_clean();
//
//                $msg[] = util::gerarPDF($relatorio, "Relatório de Patrimonios");
//            } else {
//                $msg[] = 'erro=Nenhum patrimonio cadastrado.';
//            }
        }break;
}
if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}