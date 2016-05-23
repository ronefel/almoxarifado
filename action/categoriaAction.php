<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/categoriaModel.php';

class categoriaAction extends categoriaModel {

    public static function insertCategoria($categoria) {

        $sql = "insert into categoria (
                            nome)
                   values ('{$categoria->getCategorianome()}')";

        return Transaction::runExecute($sql);

    }

    public static function updateCategoria($categoria) {

        $sql = "update categoria
                   set nome='{$categoria->getCategorianome()}'
                 where categoriaid='{$categoria->getCategoriaid()}'";

        return Transaction::runExecute($sql);

    }

    public static function deleteCategoria($categoria) {

        $sql = "DELETE FROM categoria WHERE categoriaid='{$categoria->getCategoriaid()}'";

        $result = Transaction::runExecute($sql);
        if ($result) {
            echo 'sucesso';
        } else {

            $total = "";
            $sql1 = "    SELECT count(L.categoriaid) as total
                           FROM categoria L
                     INNER JOIN departamento D
                             ON D.categoriaid = L.categoriaid
                          WHERE L.categoriaid = '{$categoria->getCategoriaid()}'";

            $result1 = Transaction::runExecute($sql1);

            if ($result1) {
                while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
                    $total = $row['total'];
                }

                echo 'Não é possível excluir esse categoria, '
                . 'esse categoria está vinculado a '
                . $total . ' departamento(s).';
            } else {
                echo 'Ouve um erro na hora de excluir o categoria.';
            }
        }

    }

    public static function listCategoria() {

        $sql = "     SELECT categoriaid,
                            nome
                       FROM categoria";

        $result = Transaction::runExecute($sql);

        $arrayCategoria = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $categoria = new categoriaModel();
            $categoria->setCategoriaid($row['categoriaid']);
            $categoria->setCategorianome($row['nome']);
            array_push($arrayCategoria, $categoria);

        }

        return $arrayCategoria;

    }

    public static function getCategoria($categoria) {

        $sql = "     SELECT categoriaid,
                            nome
                       FROM categoria
                      WHERE categoriaid='{$categoria->getCategoriaid()}'";

        $result = Transaction::runExecute($sql);

        $categoria = new categoriaModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $categoria = new categoriaModel();
            $categoria->setCategoriaid($row['categoriaid']);
            $categoria->setCategorianome($row['nome']);

        }

        return $categoria;

    }
    
    public static function isExists($categoria) {

        $sql = "SELECT * 
                  FROM categoria
                  WHERE nome ILIKE '{$categoria->getCategorianome()}'";

        $result = Transaction::runExecute($sql);

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            return true;
        }
        return false;
    }

}

?>