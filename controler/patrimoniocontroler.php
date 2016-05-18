<?php
header ('Content-type: text/html; charset=UTF-8',true);
$nivel = 1;
include_once '../config.php';

require_once '../action/patrimonioAction.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

$patrimonio = new patrimonioModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['patrimonioid'])) {
    $patrimonio->setPatrimonioid($_POST['patrimonioid']);
}

if (isset($_POST['categoriaid'])) {
    $patrimonio->setCategoriaid($_POST['categoriaid']);
}

if (isset($_POST['patrimoniodescricao'])) {
    $patrimonio->setPatrimoniodescricao($_POST['patrimoniodescricao']);
}

if (isset($_POST['serie'])) {
    $patrimonio->setSerie($_POST['serie']);
}

if (isset($_POST['marcaid'])) {
    $patrimonio->setMarcaid($_POST['marcaid']);
}

if (isset($_POST['notafiscal'])) {
    $patrimonio->setNotafiscal($_POST['notafiscal']);
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
    $patrimonio->setDepartamentoid($_POST['departamentoid']);
}

switch ($control) {

    case 'view': {

            $patrimonios = new patrimonioModel();
            $patrimonios = patrimonioAction::listPatrimonio();

            include_once '../table/patrimoniotable.php';
        }break;

    case 'novo': {

            if ($patrimonio->getPatrimoniodescricao() == "") {

                $msg[] = "O Campo 'Descrição' não pode ser vazio.<br/>";
            }

            if (!count($msg) > 0) {

                $result = patrimonioAction::insertPatrimonio($patrimonio);
                if ($result) {
                    $msg[] = 'sucesso';
                } else {
                    $msg[] = 'Ouve um erro na hora de cadastrar o patrimonio.';
                }
            }
        }break;

    case 'editar': {

            if ($patrimonio->getPatrimoniodescricao() == "") {

                $msg[] = "O Campo 'Descrição' não pode ser vazio.<br/>";
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

            $patrimonios = new patrimonioModel();
            $patrimonios = patrimonioAction::listPatrimonio();

            if (count($patrimonios) > 0) {
                $estoquetotal = 0.0;

                ob_start();
                include_once "../report/relatorio/patrimonio.php";
                $relatorio = ob_get_clean();

                $msg[] = util::gerarPDF($relatorio, "Relatório de Patrimonios");
            } else {
                $msg[] = 'erro=Nenhum patrimonio cadastrado.';
            }
        }break;
}
if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}