<?php

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/produtogrupoModel.php';

class produtogrupoAction extends produtogrupoModel {

    public static function insertProdutogrupo($produtogrupo) {

        $sql = "insert into produtogrupo (
                            nome)
                   values ('{$produtogrupo->getNome()}')";

        return Transaction::runExecute($sql);
    }

    public static function updateProdutogrupo($produtogrupo) {

        $sql = "update produtogrupo
                   set nome='{$produtogrupo->getNome()}'
                 where produtogrupoid='{$produtogrupo->getProdutogrupoid()}'";

        return Transaction::runExecute($sql);
    }

    public static function deleteProdutogrupo($produtogrupo) {

        $sql = "DELETE FROM produtogrupo WHERE produtogrupoid='{$produtogrupo->getProdutogrupoid()}'";

        $result = Transaction::runExecute($sql);
        if ($result) {
            echo 'sucesso';
        } else {

            $total = "";
            $sql1 = "    SELECT count(pg.produtogrupoid) as total
                           FROM produtogrupo pg
                     INNER JOIN produtosubgrupo psg
                             ON psg.produtogrupoid = pg.produtogrupoid
                          WHERE pg.produtogrupoid = '{$produtogrupo->getProdutogrupoid()}'";

            $result1 = Transaction::runExecute($sql1);

            if ($result1) {
                while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
                    $total = $row['total'];
                }

                echo 'Não é possível excluir esse grupo de produto, '
                . 'esse grupo de produto está vinculado a '
                . $total . ' subgrupo de produtos.';
            } else {
                echo 'Ouve um erro na hora de excluir o grupo de produto.';
            }
        }
    }

    public static function listProdutogrupo() {

        $sql = "     SELECT produtogrupoid,
                            nome
                       FROM produtogrupo";

        $result = Transaction::runExecute($sql);

        $arrayProdutogrupo = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $produtogrupo = new produtogrupoModel();
            $produtogrupo->setProdutogrupoid($row['produtogrupoid']);
            $produtogrupo->setNome($row['nome']);
            array_push($arrayProdutogrupo, $produtogrupo);
        }

        return $arrayProdutogrupo;
    }

    public static function getProdutogrupo($produtogrupo) {

        $sql = "     SELECT produtogrupoid,
                            nome
                       FROM produtogrupo
                      WHERE produtogrupoid='{$produtogrupo->getProdutogrupoid()}'";

        $result = Transaction::runExecute($sql);

        $produtogrupo = new produtogrupoModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $produtogrupo = new produtogrupoModel();
            $produtogrupo->setProdutogrupoid($row['produtogrupoid']);
            $produtogrupo->setNome($row['nome']);
        }

        return $produtogrupo;
    }

    public static function isExists($produtogrupo) {

        $sql = "SELECT * 
                  FROM produtogrupo
                  WHERE nome ILIKE '{$produtogrupo->getNome()}'";

        $result = Transaction::runExecute($sql);

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            return true;
        }
        return false;
    }

}

?>