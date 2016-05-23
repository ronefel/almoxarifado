<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/marcaModel.php';

class marcaAction extends marcaModel {

    public static function insertMarca($marca) {

        $sql = "insert into marca (
                            nome)
                   values ('{$marca->getMarcanome()}')";

        return Transaction::runExecute($sql);

    }

    public static function updateMarca($marca) {

        $sql = "update marca
                   set nome='{$marca->getMarcanome()}'
                 where marcaid='{$marca->getMarcaid()}'";

        return Transaction::runExecute($sql);

    }

    public static function deleteMarca($marca) {

        $sql = "DELETE FROM marca WHERE marcaid='{$marca->getMarcaid()}'";

        $result = Transaction::runExecute($sql);
        if ($result) {
            echo 'sucesso';
        } else {

            $total = "";
            $sql1 = "    SELECT count(L.marcaid) as total
                           FROM marca L
                     INNER JOIN departamento D
                             ON D.marcaid = L.marcaid
                          WHERE L.marcaid = '{$marca->getMarcaid()}'";

            $result1 = Transaction::runExecute($sql1);

            if ($result1) {
                while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
                    $total = $row['total'];
                }

                echo 'Não é possível excluir esse marca, '
                . 'esse marca está vinculado a '
                . $total . ' departamento(s).';
            } else {
                echo 'Ouve um erro na hora de excluir o marca.';
            }
        }

    }

    public static function listMarca() {

        $sql = "     SELECT marcaid,
                            nome
                       FROM marca";

        $result = Transaction::runExecute($sql);

        $arrayMarca = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $marca = new marcaModel();
            $marca->setMarcaid($row['marcaid']);
            $marca->setMarcanome($row['nome']);
            array_push($arrayMarca, $marca);

        }

        return $arrayMarca;

    }

    public static function getMarca($marca) {

        $sql = "     SELECT marcaid,
                            nome
                       FROM marca
                      WHERE marcaid='{$marca->getMarcaid()}'";

        $result = Transaction::runExecute($sql);

        $marca = new marcaModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $marca = new marcaModel();
            $marca->setMarcaid($row['marcaid']);
            $marca->setMarcanome($row['nome']);

        }

        return $marca;

    }
    
    public static function isExists($marca) {

        $sql = "SELECT * 
                  FROM marca
                  WHERE nome ILIKE '{$marca->getMarcanome()}'";

        $result = Transaction::runExecute($sql);

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            return true;
        }
        return false;
    }

}

?>