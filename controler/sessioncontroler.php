<?php
$arq = "config.php";
while (!file_exists($arq)) {
    $arq = "../" . $arq;
    if (file_exists($arq)) {
        include_once $arq;
        break;
    }
}
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/sessao.php';
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/action/usuarioAction.php';

$control = "";
$login = "";
$senha = "";
$usuario = new usuarioModel();

if (isset($_POST['control'])) {
    $control = $_POST['control'];
}

if (isset($_POST['login'])) {
    $login = $_POST['login'];
}

if (isset($_POST['senha'])) {
    $senha = $_POST['senha'];
}

switch ($control) {

    case 'logar': {

            if (strlen($login) > 0) {
                $usuario->setLogin($login);
            }
            if (strlen($senha) > 0) {
                $usuario->setSenha($senha);
            }

            $usuario = usuarioAction::loginUsuario($usuario);

            # efetua o processo de autenticação
            if (strlen($usuario->getUsuarioid()) > 0) {

                Sessao::set('usuario', $usuario);

                if ($usuario->getTipousuario() == 1) {
                    header('location: /almoxarifado/');
                }
                if ($usuario->getTipousuario() == 2) {
                    header('location: /almoxarifado/requisitante.php');
                }
            } else {
                # envia o usuário de volta para 
                # o form de login
                header('location: /almoxarifado?erro=1');
            }
        } break;

    case 'sair': {

            # envia o usuário para fora do sistema
            # o form de login
            Sessao::sair();
            echo 'sucesso';
            exit();
        } break;

    case 'sessao': {
            if (Sessao::existe('usuario')) {
                echo '1';
            } else {
                echo '0';
            }
            exit();
        }break;
}

$url = $_SERVER['REQUEST_URI'];
if (!Sessao::existe('usuario')) {

    include $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/login.php';
} else {

    $usuario = Sessao::get('usuario');
    if ($usuario->getTipousuario() == 2 && !strpos($url, "requisitante")) {
        header("location: /almoxarifado/requisitante.php");
    }
}
