<?php

header('Content-type: text/html; charset=UTF-8', true);

class Sessao {

    private static $instancia = array();

    /**
     * 
     * @return Session
     */
    public static function instanciar() {

        if (self::$instancia != null) {
            self::$instancia = new Sessao();
        }

        return self::$instancia;
    }

    public static function set($chave, $valor) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION[$chave] = $valor;
        session_write_close();
    }

    public static function get($chave) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $value = $_SESSION[$chave];
        session_write_close();
        return $value;
    }

    public static function existe($chave) {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION[$chave]) && (!empty($_SESSION[$chave]))) {
            session_write_close();
            return true;
        } else {
            session_write_close();
            return false;
        }
    }

    public static function sair() {
        if (!isset($_SESSION)) {
            session_start();
        }
        return session_destroy();
    }

}
