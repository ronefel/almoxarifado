<?php

$nivel = 1;
include_once '../config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

require_once '../action/fornecedorAction.php';
$fornecedor = new fornecedorModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['fornecedorid']) && $_POST['fornecedorid'] != "") {
    $fornecedor->setFornecedorid($_POST['fornecedorid']);
}

if (isset($_POST['fornecedorfantasia'])) {
    $fornecedor->setFantazia($_POST['fornecedorfantasia']);
}

if (isset($_POST['fornecedorrazao'])) {
    $fornecedor->setRazao($_POST['fornecedorrazao']);
}

if (isset($_POST['fornecedorendereco'])) {
    $fornecedor->setEndereco($_POST['fornecedorendereco']);
}

if (isset($_POST['fornecedornumero'])) {
    $fornecedor->setNumero($_POST['fornecedornumero']);
}

if (isset($_POST['fornecedorbairro'])) {
    $fornecedor->setBairro($_POST['fornecedorbairro']);
}

if (isset($_POST['fornecedorcidadeid'])) {
    $fornecedor->setCidadeid($_POST['fornecedorcidadeid']);
}

if (isset($_POST['fornecedorcnpj_cpf'])) {
    $fornecedor->setCnpj_cpf($_POST['fornecedorcnpj_cpf']);
}

if (isset($_POST['fornecedorinscricao_rg'])) {
    $fornecedor->setInscricao_rg($_POST['fornecedorinscricao_rg']);
}

if (isset($_POST['fornecedortelefone'])) {
    $fornecedor->setTelefone($_POST['fornecedortelefone']);
}

if (isset($_POST['fornecedoremail'])) {
    $fornecedor->setEmail($_POST['fornecedoremail']);
}

if (isset($_POST['fornecedorcontato'])) {
    $fornecedor->setContato($_POST['fornecedorcontato']);
}

if (isset($_POST['fornecedorgrupoid'])) {
    $fornecedor->setFornecedorgrupoid($_POST['fornecedorgrupoid']);
}

if (isset($_POST['fornecedordatacadastro'])) {

    $fornecedor->setDatacadastro($_POST['fornecedordatacadastro']);
}

if (isset($_POST['fornecedorativo'])) {
    $fornecedor->setAtivo($_POST['fornecedorativo']);
}

if (isset($_POST['fornecedorobservacao'])) {
    $fornecedor->setObservacao($_POST['fornecedorobservacao']);
}

switch ($control) {

    case 'view': {

            $fornecedores = new fornecedorModel();
            $fornecedores = fornecedorAction::listFornecedor();

            include_once '../table/fornecedortable.php';
        }break;

    case 'novo': {

            if ($fornecedor->getFantazia() == "") {

                $msg[] = "O Campo 'Nome Fantasia' não pode ser vazio.<br/>";
            }

            if ($fornecedor->getRazao() == "") {

                $msg[] = "O Campo 'Razão Social' não pode ser vazio.<br/>";
            }

            if ($fornecedor->getCidadeid() == "") {

                $msg[] = "Tem que selecionar uma 'Cidade'.<br/>";
            }

            if ($fornecedor->getFornecedorgrupoid() == "") {

                $msg[] = "Tem que informar o 'Grupo de Fornecedor'.<br/>";
            }

            if ($fornecedor->getDatacadastro() != "") {

                if (util::dataValida($fornecedor->getDatacadastro())) {
                    $fornecedor->setDatacadastro(util::dateToUS($fornecedor->getDatacadastro()));
                } else {
                    $msg[] = "Tem que informar uma data de cadastro válida.<br/>";
                }
            } else {

                $fornecedor->setDatacadastro(date("Y-m-d"));
            }

            if (!count($msg) > 0) {
                $result = fornecedorAction::insertFornecedor($fornecedor);
                if ($result) {
                    $msg[] = 'sucesso';
                } else {
                    $msg[] = 'Ouve um erro na hora de cadastrar o fornecedor.';
                }
            }
        }break;

    case 'editar': {

            if ($fornecedor->getFantazia() == "") {

                $msg[] = "O Campo 'Nome Fantasia' não pode ser vazio.<br/>";
            }

            if ($fornecedor->getRazao() == "") {

                $msg[] = "O Campo 'Razão Social' não pode ser vazio.<br/>";
            }

            if ($fornecedor->getCidadeid() == "") {

                $msg[] = "Tem que selecionar uma 'Cidade'.<br/>";
            }

            if ($fornecedor->getFornecedorgrupoid() == "") {

                $msg[] = "Tem que informar o 'Grupo de Fornecedor'.<br/>";
            }

            if ($fornecedor->getDatacadastro() != "") {

                if (util::dataValida($fornecedor->getDatacadastro())) {
                    $fornecedor->setDatacadastro(util::dateToUS($fornecedor->getDatacadastro()));
                } else {
                    $msg[] = "Tem que informar uma data de cadastro válida.<br/>";
                }
            } else {

                $fornecedor->setDatacadastro(date("Y-m-d"));
            }

            if (!count($msg) > 0) {

                $result = fornecedorAction::updateFornecedor($fornecedor);
                if ($result) {
                    $msg[] = 'sucesso';
                } else {
                    $msg[] = 'Ouve um erro na hora de atualizar o fornecedor.';
                }
            }
        }break;

    case 'excluir': {

            $result = fornecedorAction::deleteFornecedor($fornecedor);
            $msg[] = $result;
        }break;
}

if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}