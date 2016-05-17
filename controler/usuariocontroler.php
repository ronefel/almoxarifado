<?php

$nivel = 1;
include_once '../config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

require_once '../action/usuarioAction.php';
$usuario = new usuarioModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}

if (isset($_POST['usuarioid']) && $_POST['usuarioid'] != "") {
    $usuario->setUsuarioid($_POST['usuarioid']);
}

if (isset($_POST['usuarionome']) && $_POST['usuarionome'] != "") {
    $usuario->setUsuarionome($_POST['usuarionome']);
} else {
    $erro[] = "O Campo 'Nome do Usuario' não pode ser vazio.";
}

if (isset($_POST['usuariologin']) && $_POST['usuariologin'] != "") {
    $usuario->setLogin($_POST['usuariologin']);
}

if (isset($_POST['usuariosenha']) && $_POST['usuariosenha'] != "") {
    $usuario->setSenha($_POST['usuariosenha']);
}

if (isset($_POST['usuariodepartamentoid']) && $_POST['usuariodepartamentoid'] != "") {
    $usuario->setDepartamentoid($_POST['usuariodepartamentoid']);
} else {
    $erro[] = "Tem que selecionar um 'Departamento'.";
}

if (isset($_POST['usuarioemail'])) {
    $usuario->setEmail($_POST['usuarioemail']);
}

if (isset($_POST['tipousuario']) && $_POST['tipousuario'] != "") {
    $usuario->setTipousuario($_POST['tipousuario']);
} else {
    $usuario->setTipousuario(2);
}

if (isset($_POST['usuarioativo'])) {
    $usuario->setAtivo($_POST['usuarioativo']);
}

switch ($control) {

    case 'view': {

            $usuario = new usuarioModel();
            $usuario = usuarioAction::listUsuario();

            include_once '../table/usuariotable.php';
        }break;

    case 'novo': {

            if ($usuario->getUsuarionome() == "") {

                $msg[] = "O Campo 'Nome do Usuario' não pode ser vazio.<br/>";
            }

            if ($usuario->getLogin() != "") {

                if ($usuario->getSenha() == "") {

                    $msg[] = "Informe a 'Senha' para o login " . $usuario->getLogin() . ".<br/>";
                }

                if ($usuario->getEmail() == "") {

                    $msg[] = "Informe o 'Email' para o login " . $usuario->getLogin() . ".<br/>";
                }
            }

            if ($usuario->getDepartamentoid() == "") {

                $msg[] = "Tem que selecionar um 'Departamento'.<br/>";
            }

            if (!count($msg) > 0) {

                $result = usuarioAction::insertUsuario($usuario);
                if ($result) {
                    $msg[] = 'sucesso';
                } else {
                    $msg[] = 'Ouve um erro na hora de cadastrar o usuario.';
                }
            }
        }break;

    case 'editar': {

            if ($usuario->getUsuarionome() == "") {

                $msg[] = "O Campo 'Nome do Usuario' não pode ser vazio.<br/>";
            }

            if ($usuario->getLogin() != "") {

                if ($usuario->getSenha() == "") {

                    $msg[] = "Informe a 'Senha' para o login " . $usuario->getLogin() . ".<br/>";
                }

                if ($usuario->getEmail() == "") {

                    $msg[] = "Informe o 'Email' para o login " . $usuario->getLogin() . ".<br/>";
                }
            }

            if ($usuario->getDepartamentoid() == "") {

                $msg[] = "Tem que selecionar um 'Departamento'.<br/>";
            }

            if (!count($msg) > 0) {

                $result = usuarioAction::updateUsuario($usuario);
                if ($result) {
                    $msg[] = 'sucesso';
                } else {
                    $msg[] = 'Ouve um erro na hora de atualizar o usuario.';
                }
            }
        }break;

    case 'excluir': {

            $result = usuarioAction::deleteUsuario($usuario);
            $msg[] = $result;
        }break;
}

if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}
