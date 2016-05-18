<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/localModel.php';

class localAction extends localModel {

    public static function insertLocal($local) {

        $sql = "insert into local (
                            nome)
                   values ('{$local->getLocalnome()}')";

        return Transaction::runExecute($sql);

    }

    public static function updateLocal($local) {

        $sql = "update local
                   set nome='{$local->getLocalnome()}'
                 where localid='{$local->getLocalid()}'";

        return Transaction::runExecute($sql);

    }

    public static function deleteLocal($local) {

        $sql = "DELETE FROM local WHERE localid='{$local->getLocalid()}'";

        $result = Transaction::runExecute($sql);
        if ($result) {
            echo 'sucesso';
        } else {

            $total = "";
            $sql1 = "    SELECT count(L.localid) as total
                           FROM local L
                     INNER JOIN departamento D
                             ON D.localid = L.localid
                          WHERE L.localid = '{$local->getLocalid()}'";

            $result1 = Transaction::runExecute($sql1);

            if ($result1) {
                while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
                    $total = $row['total'];
                }

                echo 'Não é possível excluir esse local, '
                . 'esse local está vinculado a '
                . $total . ' departamento(s).';
            } else {
                echo 'Ouve um erro na hora de excluir o local.';
            }
        }

    }

    public static function listLocal() {

        $sql = "     SELECT localid,
                            nome
                       FROM local";

        $result = Transaction::runExecute($sql);

        $arrayLocal = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $local = new localModel();
            $local->setLocalid($row['localid']);
            $local->setLocalnome($row['nome']);
            array_push($arrayLocal, $local);

        }

        return $arrayLocal;

    }

    public static function getLocal($local) {

        $sql = "     SELECT localid,
                            nome
                       FROM local
                      WHERE localid='{$local->getLocalid()}'";

        $result = Transaction::runExecute($sql);

        $local = new localModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $local = new localModel();
            $local->setLocalid($row['localid']);
            $local->setLocalnome($row['nome']);

        }

        return $local;

    }
    
    public static function isExists($local) {

        $sql = "SELECT * 
                  FROM local
                  WHERE nome ILIKE '{$local->getLocalnome()}'";

        $result = Transaction::runExecute($sql);

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            return true;
        }
        return false;
    }

}

?>