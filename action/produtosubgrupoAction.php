<?php

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/produtosubgrupoModel.php';

class produtosubgrupoAction extends produtosubgrupoModel {

    public static function insertProdutosubgrupo($produtosubgrupo) {

        $sql = "insert into produtosubgrupo (
                            produtogrupoid,
                            nome)
                   values ('{$produtosubgrupo->getProdutogrupoid()}',
                           '{$produtosubgrupo->getNome()}')";

        return Transaction::runExecute($sql);
    }

    public static function updateProdutosubgrupo($produtosubgrupo) {

        $sql = "update produtosubgrupo
                   set produtogrupoid='{$produtosubgrupo->getProdutogrupoid()}',
                       nome='{$produtosubgrupo->getNome()}'
                 where produtosubgrupoid='{$produtosubgrupo->getProdutosubgrupoid()}'";

        return Transaction::runExecute($sql);
    }

    public static function deleteProdutosubgrupo($produtosubgrupo) {

        $sql = "DELETE FROM produtosubgrupo WHERE produtosubgrupoid='{$produtosubgrupo->getProdutosubgrupoid()}'";

        $result = Transaction::runExecute($sql);
        if ($result) {
            echo 'sucesso';
        } else {

            $total = "";
            $sql1 = "    SELECT count(psg.produtosubgrupoid) as total
                           FROM produtosubgrupo psg
                     INNER JOIN produto p
                             ON p.produtosubgrupoid = psg.produtosubgrupoid
                          WHERE psg.produtosubgrupoid = '{$produtosubgrupo->getProdutosubgrupoid()}'";

            $result1 = Transaction::runExecute($sql1);

            if ($result1) {
                while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
                    $total = $row['total'];
                }

                echo 'Não é possível excluir esse subgrupo de produto, '
                . 'esse subgrupo de produto está vinculado a '
                . $total . ' produtos.';
            } else {
                echo 'Ouve um erro na hora de excluir o subgrupo de produto.';
            }
        }
    }

    public static function listProdutosubgrupo() {

        $sql = "         SELECT psg.produtosubgrupoid, 
                                psg.nome AS subgruponome,
                                pg.produtogrupoid,
                                pg.nome AS gruponome
                           FROM produtosubgrupo psg
                     INNER JOIN produtogrupo pg
                             ON pg.produtogrupoid = psg.produtogrupoid
                       ORDER BY pg.nome, psg.nome";

        $result = Transaction::runExecute($sql);

        $arrayProdutosubgrupo = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $produtosubgrupo = new produtosubgrupoModel();
            $produtosubgrupo->setProdutosubgrupoid($row['produtosubgrupoid']);
            $produtosubgrupo->setNome($row['subgruponome']);

            $produtogrupo = new produtogrupoModel();
            $produtogrupo->setProdutogrupoid($row['produtogrupoid']);
            $produtogrupo->setNome($row['gruponome']);

            $produtosubgrupo->setProdutogrupomodel($produtogrupo);

            array_push($arrayProdutosubgrupo, $produtosubgrupo);
        }

        return $arrayProdutosubgrupo;
    }

    public static function listprodutosubgrupoToProdutogrupo($produtogrupoid) {

        $sql = "    SELECT psg.produtosubgrupoid, 
                           psg.produtogrupoid, 
                           psg.nome AS subgruponome,
                           pg.nome AS gruponome
                      FROM produtosubgrupo psg
                INNER JOIN produtogrupo pg
                        ON pg.produtogrupoid = psg.produtogrupoid
                     WHERE psg.produtogrupoid = '{$produtogrupoid}'";

        $result = Transaction::runExecute($sql);

        $arrayProdutosubgrupo = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $produtosubgrupo = new produtosubgrupoModel();
            $produtosubgrupo->setProdutosubgrupoid($row['produtosubgrupoid']);
            $produtosubgrupo->setNome($row['subgruponome']);

            array_push($arrayProdutosubgrupo, $produtosubgrupo);
        }

        return $arrayProdutosubgrupo;
    }

    public static function getProdutosubgrupo($produtosubgrupo) {

        $sql = "     SELECT psg.produtosubgrupoid, 
                            psg.nome AS subgruponome,
                            pg.produtogrupoid,
                            pg.nome AS produtogruponome
                       FROM produtosubgrupo psg
                 INNER JOIN produtogrupo pg
                         ON pg.produtogrupoid = psg.produtogrupoid
                      WHERE psg.produtosubgrupoid = '{$produtosubgrupo->getProdutosubgrupoid()}'";

        $result = Transaction::runExecute($sql);

        $produtosubgrupo = new produtosubgrupoModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $produtosubgrupo = new produtosubgrupoModel();
            $produtosubgrupo->setProdutosubgrupoid($row['produtosubgrupoid']);
            $produtosubgrupo->setNome($row['subgruponome']);

            $produtogrupo = new produtogrupoModel();
            $produtogrupo->setProdutogrupoid($row['produtogrupoid']);
            $produtogrupo->setNome($row['produtogruponome']);

            $produtosubgrupo->setProdutogrupomodel($produtogrupo);
        }

        return $produtosubgrupo;
    }

    public static function isExists($produtosubgrupo) {

        $sql = "SELECT * 
                  FROM produtosubgrupo
                 WHERE nome ILIKE '{$produtosubgrupo->getNome()}'
                   AND produtogrupoid = '{$produtosubgrupo->getProdutogrupoid()}'";

        $result = Transaction::runExecute($sql);

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            return true;
        }
        return false;
    }

}

?>