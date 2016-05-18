<?php
header ('Content-type: text/html; charset=UTF-8',true);
require_once 'Connection.php';

/* clase dbTransaction
 * esta classe prevê os métodos necessários para manipular transações.
 */

class Transaction extends Connection {

    private static $conn;  //conexão ativa

    /* método open()
     * abre uma transação e uma conexão ao BD
     */

    public static function open() {

        //abre uma conexão e armazena na propriedade estática $conn
        if (empty(self::$conn)) {
            self::$conn = Connection::open();
            //inicia a transação
            self::$conn->beginTransaction();
        }
    }

    /* método get()
     * retorna a conexão ativa da transação
     */

    public static function get() {

        //retorna a conexão ativa
        return self::$conn;
    }

    public static function runPrepare($sql, $args) {
        try {
            Transaction::open();

            $prepare = Transaction::get()->prepare($sql);

            $prepare->execute($args);
            
            $errorInfo = $prepare->errorInfo();
            
            if($errorInfo[0] != 0){
                throw new Exception($errorInfo[2]);
            }

            Transaction::close();

            return $prepare;
        } catch (Exception $exc) {
            Transaction::rollback();
            //exibe a mensagem de erro
            echo "Erro!: " . $exc->getMessage() . "<br/>";
            die();
        }
    }

    /* método runExecute()
     * Executa o comando SQL
     */

    public static function runExecute($sql) {

        try {

            Transaction::open();

            $result = Transaction::get()->query($sql);

            Transaction::close();

            return $result;
        } catch (Exception $exc) {
            Transaction::rollback();
            //exibe a mensagem de erro
            echo "Erro!: " . $exc->getMessage() . "<br/>";
            die();
        }
    }

    /* método rollback()
     * desfaz todas operações realizadas na transação
     */

    public static function rollback() {

        if (self::$conn) {
            //desfaz as operações realizadas durante a transação
            self::$conn->rollback();
            self::$conn = null;
        }
    }

    /* método close()
     * aplica todas operações realizadas e fecha a transação
     */

    public static function close() {

        if (self::$conn) {
            //aplica as operações realizadas
            //durante a transação
            self::$conn->commit();
            self::$conn = null;
        }
    }

}

?>
