<?php

$nivel = 1;
include_once '../config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/util/autenticador.php';

require_once '../action/usuarioAction.php';
$usr = new usuarioModel();

require_once '../action/temaAction.php';
$tema = new temaModel();
$msg = array();
$control = "";

if (isset($_POST['control']) && !empty($_POST['control'])) {

    $control = $_POST['control'];
}
if (isset($_POST['temaid']) && !empty($_POST['temaid'])) {

    $usr->setTemaid($_POST['temaid']);
}
if (isset($_POST['usuarioid']) && !empty($_POST['usuarioid'])) {

    $usr->setUsuarioid($_POST['usuarioid']);
}

switch ($control) {

    case 'view': {


            $tema = temaAction::listTema();
            $usr = usuarioAction::getUsuario($usuario);


            include_once '../form/temaform.php';
        }break;

    case 'editar': {

            $result = usuarioAction::updateUsuario($usr);
            $msg[] = "sucesso";
        }break;
}

if (count($msg) > 0) {
    foreach ($msg AS $s) {
        echo $s;
    }
}
