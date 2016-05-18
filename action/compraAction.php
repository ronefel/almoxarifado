<?php

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/compraModel.php';

class compraAction extends compraModel {

    public static function insertCompra($compra) {

        $args = array();

        $sql = "insert into compra (";

        if (strlen($compra->getFornecedorid()) > 0) {
            $sql .= 'fornecedorid,';
            $args[] = $compra->getFornecedorid();
        }
        if (strlen($compra->getCompraemissao()) > 0) {
            $sql .= 'emissao,';
            $args[] = $compra->getCompraemissao();
        }
        if (strlen($compra->getCompraaprovacao()) > 0) {
            $sql .= 'aprovacao,';
            $args[] = $compra->getCompraaprovacao();
        }
        if (strlen($compra->getCompraentrega()) > 0) {
            $sql .= 'entrega,';
            $args[] = $compra->getCompraentrega();
        }
        if (strlen($compra->getComprasituacao()) > 0) {
            $sql .= 'situacao,';
            $args[] = $compra->getComprasituacao();
        }

        $sql = substr($sql, 0, strlen($sql) - 1) . ') VALUES (';
        for ($i = 0; $i < count($args); $i++) {
            $sql .= '?,';
        }

        $sql = substr($sql, 0, strlen($sql) - 1) . ') returning compraid';

        $result = Transaction::runPrepare($sql, $args);

        $id = $result->fetch(PDO::FETCH_OBJ);

        return $id->compraid;
    }

    public static function updateCompra($compra) {

        $args = array();

        $sql = 'update compra set ';

        if (isset($compra->fornecedorid)) {
            $sql .= 'fornecedorid = ?, ';
            $args[] = $compra->getFornecedorid();
        }
        if (isset($compra->emissao)) {
            $sql .= 'emissao = ?, ';
            $args[] = $compra->getCompraemissao();
        }
        if (isset($compra->aprovacao)) {
            $sql .= 'aprovacao = ?, ';
            $args[] = $compra->getCompraaprovacao();
        }
        if (isset($compra->entrega)) {
            $sql .= 'entrega = ?, ';
            $args[] = $compra->getCompraentrega();
        }
        if (isset($compra->situacao)) {
            $sql .= 'situacao = ?, ';
            $args[] = $compra->getComprasituacao();
        }
        if (isset($compra->reprovacaotxt)) {
            $sql .= 'reprovacaotxt = ?, ';
            $args[] = $compra->getComprareprovacaotxt();
        }
        if (isset($compra->reprovacao)) {
            $sql .= 'reprovacao = ?, ';
            $args[] = $compra->getComprareprovacao();
        }

        $args[] = $compra->getCompraid();

        for ($i = 0; $i < count($args); $i++) {
            if (strlen($args[$i]) == 0) {
                $args[$i] = null; // Aqui faz o elemento vazio ficar NULL
            }
        }

        $sql = substr($sql, 0, strlen($sql) - 2) . ' where compraid = ?';

        return Transaction::runPrepare($sql, $args);
    }

    public static function deleteCompra($compra) {

        $sql = "DELETE FROM compra WHERE compraid='{$compra->getCompraid()}'";

        return Transaction::runExecute($sql);
    }

    public static function listCompra($datainicial = '2015-01-01', $datafinal = '2200-12-30') {

        $sql = "     SELECT C.compraid,
                            C.emissao,
                            C.aprovacao,
                            C.entrega,
                            C.situacao,
                            C.reprovacaotxt,
                            C.reprovacao,
                            CASE C.situacao
                                WHEN 1 THEN 'Aberta'
                                WHEN 2 THEN 'Aprovada'
                                WHEN 3 THEN 'Recebida'
                                WHEN 4 THEN 'Reprovada'
                                WHEN 5 THEN 'Cancelada'
                            END AS situacaonome,
                            F.fornecedorid,
                            F.fantazia AS fornecedorfantazia,
                            FG.fornecedorgrupoid,
                            FG.nome AS fornecedorgruponome
                       FROM compra C
                 INNER JOIN fornecedor F
                         ON F.fornecedorid = C.fornecedorid
                 INNER JOIN fornecedorgrupo FG
                         ON FG.fornecedorgrupoid = F.fornecedorgrupoid
                      WHERE C.emissao BETWEEN '{$datainicial}' AND '{$datafinal}'
                   ORDER BY C.situacao ASC, C.emissao ASC";

        $result = Transaction::runExecute($sql);

        $arrayCompra = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $compra = new compraModel();
            $compra->setCompraid($row['compraid']);
            $compra->setCompraemissao($row['emissao']);
            $compra->setCompraaprovacao($row['aprovacao']);
            $compra->setCompraentrega($row['entrega']);
            $compra->setComprasituacao($row['situacao']);
            $compra->setComprasituacaonome($row['situacaonome']);
            $compra->setFornecedorid($row['fornecedorid']);
            $compra->setFantazia($row['fornecedorfantazia']);
            $compra->setFornecedorgrupoid($row['fornecedorgrupoid']);
            $compra->setFornecedorgruponome($row['fornecedorgruponome']);
            $compra->setComprareprovacaotxt($row['reprovacaotxt']);
            $compra->setComprareprovacao($row['reprovacao']);
            array_push($arrayCompra, $compra);
        }

        return $arrayCompra;
    }

    public static function getCompra($compra) {

        $sql = "     SELECT C.compraid,
                            C.emissao,
                            C.aprovacao,
                            C.entrega,
                            C.situacao,
                            C.reprovacaotxt,
                            C.reprovacao,
                            CASE C.situacao
                                WHEN 1 THEN 'Aberta'
                                WHEN 2 THEN 'Aprovada'
                                WHEN 3 THEN 'Entregue'
                                WHEN 4 THEN 'Reprovada'
                                WHEN 5 THEN 'Cancelada'
                            END AS situacaonome,
                            F.fornecedorid,
                            F.fantazia AS fornecedorfantazia,
                            FG.fornecedorgrupoid,
                            FG.nome AS fornecedorgruponome
                       FROM compra C
                 INNER JOIN fornecedor F
                         ON F.fornecedorid = C.fornecedorid
                 INNER JOIN fornecedorgrupo FG
                         ON FG.fornecedorgrupoid = F.fornecedorgrupoid
                      WHERE compraid='{$compra->getCompraid()}'";

        $result = Transaction::runExecute($sql);

        $compra = new compraModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $compra = new compraModel();
            $compra->setCompraid($row['compraid']);
            $compra->setCompraemissao($row['emissao']);
            $compra->setCompraaprovacao($row['aprovacao']);
            $compra->setCompraentrega($row['entrega']);
            $compra->setComprasituacao($row['situacao']);
            $compra->setComprasituacaonome($row['situacaonome']);
            $compra->setFornecedorid($row['fornecedorid']);
            $compra->setFantazia($row['fornecedorfantazia']);
            $compra->setFornecedorgrupoid($row['fornecedorgrupoid']);
            $compra->setFornecedorgruponome($row['fornecedorgruponome']);
            $compra->setComprareprovacaotxt($row['reprovacaotxt']);
            $compra->setComprareprovacao($row['reprovacao']);
        }

        return $compra;
    }

    public static function searchCompra($filters) {

        $sql = "     SELECT C.compraid,
                            C.emissao,
                            C.aprovacao,
                            C.entrega,
                            C.situacao,
                            C.reprovacaotxt,
                            C.reprovacao,
                            CASE C.situacao
                                WHEN 1 THEN 'Aberta'
                                WHEN 2 THEN 'Aprovada'
                                WHEN 3 THEN 'Recebida'
                                WHEN 4 THEN 'Reprovada'
                                WHEN 5 THEN 'Cancelada'
                            END AS situacaonome,
                            F.fornecedorid,
                            F.fantazia AS fornecedorfantazia,
                            FG.fornecedorgrupoid,
                            FG.nome AS fornecedorgruponome,
                            compravalortotal(C.compraid) AS compravalortotal
                       FROM compra C
                 INNER JOIN fornecedor F
                         ON F.fornecedorid = C.fornecedorid
                 INNER JOIN fornecedorgrupo FG
                         ON FG.fornecedorgrupoid = F.fornecedorgrupoid";
        
        $where = "";
        $args = array();

        if (strlen($filters->datainicial) > 0 && strlen($filters->datafinal) > 0) {

            $where .= ' AND C.emissao BETWEEN ? AND ? ';
            $args[] = $filters->datainicial;
            $args[] = $filters->datafinal;
        }

        if (strlen($filters->compraid) > 0) {

            $where .= ' AND C.compraid = ? ';
            $args[] = $filters->compraid;
        }

        if (strlen($filters->fornecedorid) > 0) {

            $where .= ' AND F.fornecedorid = ? ';
            $args[] = $filters->fornecedorid;
        }

        if (is_array($filters->situacao) && count($filters->situacao) > 0) {

            $ofs = implode(',', $filters->situacao);
            $where .= " AND C.situacao IN ( {$ofs} )";
        }

        if (strlen($where) > 0) {
            $sql .= ' WHERE ' . substr($where, 4);
        }

        $sql .= ' ORDER BY C.situacao DESC, C.emissao ASC';

        $result = Transaction::runPrepare($sql, $args);

        $arrayCompra = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $compra = new compraModel();
            $compra->setCompraid($row['compraid']);
            $compra->setCompraemissao($row['emissao']);
            $compra->setCompraaprovacao($row['aprovacao']);
            $compra->setCompraentrega($row['entrega']);
            $compra->setComprasituacao($row['situacao']);
            $compra->setComprasituacaonome($row['situacaonome']);
            $compra->setFornecedorid($row['fornecedorid']);
            $compra->setFantazia($row['fornecedorfantazia']);
            $compra->setFornecedorgrupoid($row['fornecedorgrupoid']);
            $compra->setFornecedorgruponome($row['fornecedorgruponome']);
            $compra->setComprareprovacaotxt($row['reprovacaotxt']);
            $compra->setComprareprovacao($row['reprovacao']);
            $compra->setCompravalortotal($row['compravalortotal']);
            array_push($arrayCompra, $compra);
        }

        return $arrayCompra;
    }

}

?>