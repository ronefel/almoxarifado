<?php
header ('Content-type: text/html; charset=UTF-8',true);
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot .'/controler/sessioncontroler.php';
if (!Sessao::existe('usuario')) {
    //echo "<h3>Por favor refaça o login <a href='/'>aqui.</a></h3>";
    exit();
}

$usuario = Sessao::get('usuario');
if ($nivel > 0) {
    if ($usuario->getTipousuario() != $nivel) {
        echo '<h3>Acesso negado!</h3>';
        exit();
    }
}