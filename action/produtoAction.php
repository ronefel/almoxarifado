<?php
header ('Content-type: text/html; charset=UTF-8',true);
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/produtoModel.php';

class produtoAction extends produtoModel {

    public static function insertProduto($produto) {

        $args = array();

        $sql = "insert into produto (";

        if (strlen($produto->getProdutosubgrupoid()) > 0) {
            $sql .= 'produtosubgrupoid,';
            $args[] = $produto->getProdutosubgrupoid();
        }
        if (strlen($produto->getProdutogrupoid()) > 0) {
            $sql .= 'produtogrupoid,';
            $args[] = $produto->getProdutogrupoid();
        }
        if (strlen($produto->getProdutonome()) > 0) {
            $sql .= 'nome,';
            $args[] = $produto->getProdutonome();
        }
        if (strlen($produto->getUnd()) > 0) {
            $sql .= 'und,';
            $args[] = $produto->getUnd();
        }
        if (strlen($produto->getCustomedio()) > 0) {
            $sql .= 'customedio,';
            $args[] = $produto->getCustomedio();
        }
        if (strlen($produto->getCodigobarras()) > 0) {
            $sql .= 'codigobarras,';
            $args[] = $produto->getCodigobarras();
        }
        if (strlen($produto->getValidade()) > 0) {
            $sql .= 'validade,';
            $args[] = $produto->getValidade();
        }
        if (strlen($produto->getObservacoes()) > 0) {
            $sql .= 'observacoes,';
            $args[] = $produto->getObservacoes();
        }
        if (strlen($produto->getAtivo()) > 0) {
            $sql .= 'ativo,';
            $args[] = $produto->getAtivo();
        }
        if (strlen($produto->getEstoqueminimo()) > 0) {
            $sql .= 'estoqueminimo,';
            $args[] = $produto->getEstoqueminimo();
        }
        if (strlen($produto->getEstoquemaximo()) > 0) {
            $sql .= 'estoquemaximo,';
            $args[] = $produto->getEstoquemaximo();
        }
        if (strlen($produto->getEstoqueatual()) > 0) {
            $sql .= 'estoqueatual,';
            $args[] = $produto->getEstoqueatual();
        }
        
        if (strlen($produto->getMarcaid()) > 0) {
            $sql .= 'marcaid,';
            $args[] = $produto->getMarcaid();
        }

        $sql = substr($sql, 0, strlen($sql) - 1) . ') VALUES (';
        for ($i = 0; $i < count($args); $i++) {
            $sql .= '?,';
        }

        $sql = substr($sql, 0, strlen($sql) - 1) . ')';

        return Transaction::runPrepare($sql, $args);
    }

    public static function updateProduto($produto) {

        $args = array();

        $sql = 'update produto set ';

        if (isset($produto->produtosubgrupoid)) {
            $sql .= 'produtosubgrupoid = ?, ';
            $args[] = $produto->getProdutosubgrupoid();
        }
        if (isset($produto->produtogrupoid)) {
            $sql .= 'produtogrupoid = ?, ';
            $args[] = $produto->getProdutogrupoid();
        }
        if (isset($produto->produtonome)) {
            $sql .= 'nome = ?, ';
            $args[] = $produto->getProdutonome();
        }
        if (isset($produto->und)) {
            $sql .= 'und = ?, ';
            $args[] = $produto->getUnd();
        }
        if (isset($produto->customedio)) {
            $sql .= 'customedio = ?, ';
            $args[] = $produto->getCustomedio();
        }
        if (isset($produto->codigobarras)) {
            $sql .= 'codigobarras = ?, ';
            $args[] = $produto->getCodigobarras();
        }
        if (isset($produto->validade)) {
            $sql .= 'validade = ?, ';
            $args[] = $produto->getValidade();
        }
        if (isset($produto->observacoes)) {
            $sql .= 'observacoes = ?, ';
            $args[] = $produto->getObservacoes();
        }
        if (isset($produto->ativo)) {
            $sql .= 'ativo = ?, ';
            $args[] = $produto->getAtivo();
        }
        if (isset($produto->estoqueminimo)) {
            $sql .= 'estoqueminimo = ?, ';
            $args[] = $produto->getEstoqueminimo();
        }
        if (isset($produto->estoquemaximo)) {
            $sql .= 'estoquemaximo = ?, ';
            $args[] = $produto->getEstoquemaximo();
        }
        if (isset($produto->estoqueatual)) {
            $sql .= 'estoqueatual = ?, ';
            $args[] = $produto->getEstoqueatual();
        }
        if ($produto->getMarcaid() != null) {
            $sql .= 'marcaid = ?, ';
            $args[] = $produto->getMarcaid();
        }

        $args[] = $produto->getProdutoid();

        for($i=0;$i<count($args);$i++) {
            if (strlen($args[$i]) == 0) {
                $args[$i] = null; // Aqui faz o elemento vazio ficar NULL
            }
        }

        $sql = substr($sql, 0, strlen($sql) - 2) . ' where produtoid = ?';

        return Transaction::runPrepare($sql, $args);
    }

    public static function deleteProduto($produto) {

        $sql = "DELETE FROM produto WHERE produtoid='{$produto->getProdutoid()}'";

        $result = Transaction::runExecute($sql);
        if ($result) {
            echo 'sucesso';
        } else {

            $total = "";
            $sql1 = "    SELECT count(P.produtoid) as total
                           FROM produto P
                     INNER JOIN estoquemovimento E
                             ON E.produtoid = P.produtoid
                          WHERE P.produtoid = '{$produto->getProdutoid()}'";

            $result1 = Transaction::runExecute($sql1);

            if ($result1) {
                while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
                    $total = $row['total'];
                }

                echo 'Não é possível excluir esse produto, '
                . 'esse produto está vinculado a '
                . $total . ' movimento de estoque.';
            } else {
                echo 'Ouve um erro na hora de excluir o produto.';
            }
        }
    }

    public static function listProduto() {

        $sql = "     SELECT P.produtoid,
                            P.produtosubgrupoid,
                            P.produtogrupoid,
                            P.nome,
                            P.und,
                            M.marcaid,
                            M.nome AS marcanome,
                            P.customedio,
                            valormedio(P.produtoid),
                            P.codigobarras,
                            P.validade,
                            P.observacoes,
                            P.ativo,
                            P.estoqueminimo,
                            P.estoquemaximo,
                            ((SELECT CASE 
                                         WHEN
                                             SUM(EME.quantidade) IS NULL THEN 0
                                         ELSE
                                             SUM(EME.quantidade)
                                     END
                                FROM estoquemovimento EME
                               WHERE EME.operacao = 1
                                 AND EME.produtoid = P.produtoid) -
                             (SELECT CASE 
                                         WHEN
                                             SUM(EMS.quantidade) IS NULL THEN 0
                                         ELSE
                                             SUM(EMS.quantidade) 
                                     END
                                FROM estoquemovimento EMS
                               WHERE EMS.operacao = 2
                                 AND EMS.produtoid = P.produtoid)) AS estoqueatual
                       FROM produto P
                  LEFT JOIN marca M
                         ON M.marcaid = P.marcaid
                   ORDER BY P.nome";

        $result = Transaction::runExecute($sql);

        $arrayProduto = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $produto = new produtoModel();
            $produto->setProdutoid($row['produtoid']);
            $produto->setProdutosubgrupoid($row['produtosubgrupoid']);
            $produto->setProdutogrupoid($row['produtogrupoid']);
            $produto->setProdutonome($row['nome']);
            $produto->setUnd($row['und']);
            $produto->setMarcaid($row['marcaid']);
            $produto->setMarcanome($row['marcanome']);
            $produto->setCustomedio($row['customedio']);
            $produto->setValormedio($row['valormedio']);
            $produto->setCodigobarras($row['codigobarras']);
            $produto->setValidade($row['validade']);
            $produto->setObservacoes($row['observacoes']);
            $produto->setAtivo($row['ativo']);
            $produto->setEstoqueminimo($row['estoqueminimo']);
            $produto->setEstoquemaximo($row['estoquemaximo']);
            $produto->setEstoqueatual($row['estoqueatual']);
            array_push($arrayProduto, $produto);
        }

        return $arrayProduto;
    }

    public static function getProduto($produto) {

        $sql = "     SELECT P.produtoid,
                            P.produtosubgrupoid,
                            P.produtogrupoid,
                            P.nome,
                            P.und,
                            M.marcaid,
                            M.nome AS marcanome,
                            P.customedio,
                            P.codigobarras,
                            P.validade,
                            P.observacoes,
                            P.ativo,
                            P.estoqueminimo,
                            P.estoquemaximo,
                            estoqueatual(P.produtoid) AS estoqueatual
                       FROM produto P
                  LEFT JOIN marca M
                         ON M.marcaid = P.marcaid
                      WHERE P.produtoid='{$produto->getProdutoid()}'";

        $result = Transaction::runExecute($sql);

        $produto = new produtoModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $produto = new produtoModel();
            $produto->setProdutoid($row['produtoid']);
            $produto->setProdutosubgrupoid($row['produtosubgrupoid']);
            $produto->setProdutogrupoid($row['produtogrupoid']);
            $produto->setProdutonome($row['nome']);
            $produto->setUnd($row['und']);
            $produto->setMarcaid($row['marcaid']);
            $produto->setMarcanome($row['marcanome']);
            $produto->setCustomedio($row['customedio']);
            $produto->setCodigobarras($row['codigobarras']);
            $produto->setValidade($row['validade']);
            $produto->setObservacoes($row['observacoes']);
            $produto->setAtivo($row['ativo']);
            $produto->setEstoqueminimo($row['estoqueminimo']);
            $produto->setEstoquemaximo($row['estoquemaximo']);
            $produto->setEstoqueatual($row['estoqueatual']);
        }

        return $produto;
    }

}

?>