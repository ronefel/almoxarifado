<?php

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/fornecedorgrupoModel.php';

class fornecedorgrupoAction extends fornecedorgrupoModel {

    public static function insertFornecedorgrupo($fornecedorgrupo) {

        $sql = "insert into fornecedorgrupo (
                            nome)
                   values ('{$fornecedorgrupo->getFornecedorgruponome()}')";

        return Transaction::runExecute($sql);
    }

    public static function updateFornecedorgrupo($fornecedorgrupo) {

        $sql = "update fornecedorgrupo
                   set nome='{$fornecedorgrupo->getFornecedorgruponome()}'
                 where fornecedorgrupoid='{$fornecedorgrupo->getFornecedorgrupoid()}'";

        return Transaction::runExecute($sql);
    }

    public static function deleteFornecedorgrupo($fornecedorgrupo) {

        $sql = "DELETE FROM fornecedorgrupo WHERE fornecedorgrupoid='{$fornecedorgrupo->getFornecedorgrupoid()}'";

        $result = Transaction::runExecute($sql);
        if ($result) {
            echo 'sucesso';
        } else {

            $total = "";
            $sql1 = "    SELECT count(FG.fornecedorgrupoid) as total
                           FROM fornecedorgrupo FG
                     INNER JOIN fornecedor F
                             ON F.fornecedorgrupoid = FG.fornecedorgrupoid
                          WHERE FG.fornecedorgrupoid = '{$fornecedorgrupo->getFornecedorgrupoid()}'";

            $result1 = Transaction::runExecute($sql1);

            if ($result1) {
                while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
                    $total = $row['total'];
                }

                echo 'Não é possível excluir esse grupo de fornecedor, '
                . 'esse grupo de fornecedor está vinculado a '
                . $total . ' fornecedor(es).';
            } else {
                echo 'Ouve um erro na hora de excluir o grupo de fornecedor.';
            }
        }
    }

    public static function listFornecedorgrupo() {

        $sql = "     SELECT fornecedorgrupoid,
                            nome
                       FROM fornecedorgrupo
                       ORDER BY nome";

        $result = Transaction::runExecute($sql);

        $arrayFornecedorgrupo = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $fornecedorgrupo = new fornecedorgrupoModel();
            $fornecedorgrupo->setFornecedorgrupoid($row['fornecedorgrupoid']);
            $fornecedorgrupo->setFornecedorgruponome($row['nome']);
            array_push($arrayFornecedorgrupo, $fornecedorgrupo);
        }

        return $arrayFornecedorgrupo;
    }

    public static function getFornecedorgrupo($fornecedorgrupo) {

        $sql = "     SELECT fornecedorgrupoid,
                            nome
                       FROM fornecedorgrupo
                      WHERE fornecedorgrupoid='{$fornecedorgrupo->getFornecedorgrupoid()}'";

        $result = Transaction::runExecute($sql);

        $fornecedorgrupo = new fornecedorgrupoModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $fornecedorgrupo = new fornecedorgrupoModel();
            $fornecedorgrupo->setFornecedorgrupoid($row['fornecedorgrupoid']);
            $fornecedorgrupo->setFornecedorgruponome($row['nome']);
        }

        return $fornecedorgrupo;
    }

    public static function isExists($fornecedorgrupo) {

        $sql = "SELECT * 
                  FROM fornecedorgrupo
                  WHERE nome ILIKE '{$fornecedorgrupo->getFornecedorgruponome()}'";

        $result = Transaction::runExecute($sql);

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            return true;
        }
        return false;
    }

}

?>