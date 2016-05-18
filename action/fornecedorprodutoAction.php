<?php 

require_once './fornecedorprodutoModel.php';

class fornecedorprodutoAction extends fornecedorprodutoModel {

    public static function insertFornecedorproduto($fornecedorproduto) {

        $sql = "insert into fornecedorproduto (
                            produtoid)
                   values ('{$fornecedorproduto->getProdutoid()}')";

        return siteTransaction::runExecute($sql);

    }

    public static function updateFornecedorproduto($fornecedorproduto) {

        $sql = "update fornecedorproduto
                   set produtoid='{$fornecedorproduto->getProdutoid()}'
                 where fornecedorid='{$fornecedorproduto->getFornecedorid()}'";

        return siteTransaction::runExecute($sql);

    }

    public static function deleteFornecedorproduto($fornecedorproduto) {

        $sql = "DELETE FROM fornecedorproduto WHERE fornecedorid='{$fornecedorproduto->getFornecedorid()}'";

        return siteTransaction::runExecute($sql);

    }

    public static function listFornecedorproduto() {

        $sql = "     SELECT fornecedorid,
                            produtoid
                       FROM fornecedorproduto";

        $result = siteTransaction::runExecute($sql);

        $arrayFornecedorproduto = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $fornecedorproduto = new fornecedorprodutoModel();
            $fornecedorproduto->setFornecedorid($row['fornecedorid']);
            $fornecedorproduto->setProdutoid($row['produtoid']);
            array_push($arrayFornecedorproduto, $fornecedorproduto);

        }

        return $arrayFornecedorproduto;

    }

    public static function getFornecedorproduto($fornecedorproduto) {

        $sql = "     SELECT fornecedorid,
                            produtoid
                       FROM fornecedorproduto
                      WHERE fornecedorid='{$fornecedorproduto->getFornecedorid()}'";

        $result = siteTransaction::runExecute($sql);

        $fornecedorproduto = new fornecedorprodutoModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $fornecedorproduto = new fornecedorprodutoModel();
            $fornecedorproduto->setFornecedorid($row['fornecedorid']);
            $fornecedorproduto->setProdutoid($row['produtoid']);

        }

        return $fornecedorproduto;

    }

}

?>