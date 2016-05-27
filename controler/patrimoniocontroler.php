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
    if (is_numeric($_POST['patrimonioid'])) {
        $patrimonio->setPatrimonioid($_POST['patrimonioid']);
    }
}

if (isset($_POST['produtoid'])) {
    if (is_numeric($_POST['produtoid'])) {
        $patrimonio->setProdutoid($_POST['produtoid']);
    }
}

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
    if (is_numeric($_POST['departamentoid'])) {
        $patrimonio->setDepartamentoid($_POST['departamentoid']);
    }
}

if (isset($_POST['fornecedorid'])) {
    if (is_numeric($_POST['fornecedorid'])) {
        $patrimonio->setFornecedorid($_POST['fornecedorid']);
    }
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

            $filters = new stdClass();

//            if (isset($_POST['operacao'])) {
//                $operacao = $_POST['operacao'];
//                for ($i = 0; $i < count($operacao); $i++) {
//
//                    $filters->operacao[$i] = $operacao[$i];
//                }
//            } else {
//                $filters->operacao = "";
//            }

            if (strlen($patrimonio->getPatrimonioid()) > 0) {
                $filters->patrimonioid = $patrimonio->getPatrimonioid();
            } else {
                $filters->patrimonioid = "";
            }

            if (strlen($patrimonio->getProdutoid()) > 0) {
                $filters->produtoid = $patrimonio->getProdutoid();
            } else {
                $filters->produtoid = "";
            }

            if (strlen($patrimonio->getDepartamentoid()) > 0) {
                $filters->departamentoid = $patrimonio->getDepartamentoid();
            } else {
                $filters->departamentoid = "";
            }

            if (isset($_POST['localid']) && is_numeric($_POST['localid'])) {
                $filters->localid = $_POST['localid'];
            } else {
                $filters->localid = "";
            }

            if (isset($_POST['datacomprainicial'])) {
                $filters->datacomprainicial = $_POST['datacomprainicial'];
            } else {
                $filters->datacomprainicial = "";
            }

            if (isset($_POST['datacomprafinal'])) {
                $filters->datacomprafinal = $_POST['datacomprafinal'];
            } else {
                $filters->datacomprafinal = "";
            }

            if (isset($_POST['dataimplantacaoinicial'])) {
                $filters->dataimplantacaoinicial = $_POST['dataimplantacaoinicial'];
            } else {
                $filters->dataimplantacaoinicial = "";
            }

            if (isset($_POST['dataimplantacaofinal'])) {
                $filters->dataimplantacaofinal = $_POST['dataimplantacaofinal'];
            } else {
                $filters->dataimplantacaofinal = "";
            }

            if (isset($_POST['fimgarantiainicial'])) {
                $filters->fimgarantiainicial = $_POST['fimgarantiainicial'];
            } else {
                $filters->fimgarantiainicial = "";
            }

            if (isset($_POST['fimgarantiafinal'])) {
                $filters->fimgarantiafinal = $_POST['fimgarantiafinal'];
            } else {
                $filters->fimgarantiafinal = "";
            }

            $pagina = "A4";
            $obs = FALSE;
            if (isset($_POST['exibeobs'])) {
                $pagina = "A4-L";
                $obs = TRUE;
            }


            $patrimonios = new patrimonioModel();
            $patrimonios = patrimonioAction::searchPatrimonio($filters);

            if (count($patrimonios) > 0) {

                ob_start();
                include_once "../report/relatorio/patrimonio.php";
                $relatorio = ob_get_clean();

                $msg[] = util::gerarPDF($relatorio, "Relatório de Patrimonios", $pagina);
            } else {
                $msg[] = 'erro=Não foram encontrados registros para a geração deste documento.';
            }
        }break;

    case 'report': {

            include_once '../form/relatorio/patrimonioreportform.php';
        }break;
}

if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}