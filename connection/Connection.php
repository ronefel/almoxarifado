<?php

header('Content-type: text/html; charset=UTF-8', true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/almoxarifado/util/util.php';
/*
 * classe dbConnection 
 * gerencia conexões com bancos de dados através de arquivos de configuração.
 */

class Connection extends util {
    /* método open()
     * recebe o nome do banco de dados e instancia o objeto PDO correspondente
     */

    public static function open() {

        $arquivo = $_SERVER['DOCUMENT_ROOT'] . "/almoxarifado/connection/connection.ini";

        //verifica se existe arquivo de configuração para este banco de dados
        if (file_exists($arquivo)) {
            //lê o INI e retorna um array
            $db = parse_ini_file($arquivo);
        } else {
            //se não existir, lança um erro
            throw new Exception("Arquivo connection.ini não encontrado!");
        }

        //lê as informações contidas no arquivo
        $user = $db['user'];
        $pass = $db['pass'];
        $name = $db['name'];
        $host = $db['host'];
        $type = $db['type'];

        //define a constante host para o sistema mudar o tema quando estiver em localhost
        if (!defined("DBHOST")) {
            define("DBHOST", $host);
        }

        //descobre qual o tipo (driver) de banco de dados a ser utilizado
        switch ($type) {
            case 'pgsql':
                $conn = new PDO("pgsql:dbname={$name};user={$user};password={$pass};host={$host}");
                break;
            case "mysql":
                $conn = new PDO("mysql:host={$host};port=3307;dbname={$name}", $user, $pass);
                break;
        }

        //define para que o PDO lance exeções na ocorrência de erros
        //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //retorna o objeto instanciado.
        return $conn;
    }

}

?>
