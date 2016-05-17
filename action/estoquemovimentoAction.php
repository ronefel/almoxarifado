<?php

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/estoquemovimentoModel.php';

class estoquemovimentoAction extends estoquemovimentoModel {

    public static function insertEstoquemovimento($estoquemovimento) {

        $args = array();

        $sql = "insert into estoquemovimento (";

        if (strlen($estoquemovimento->getEM_produtoid()) > 0) {
            $sql .= 'produtoid,';
            $args[] = $estoquemovimento->getEM_produtoid();
        }
        if (strlen($estoquemovimento->getRequisicaoid()) > 0) {
            $sql .= 'requisicaoid,';
            $args[] = $estoquemovimento->getRequisicaoid();
        }
        if (strlen($estoquemovimento->getOperacao()) > 0) {
            $sql .= 'operacao,';
            $args[] = $estoquemovimento->getOperacao();
        }
        if (strlen($estoquemovimento->getQuantidade()) > 0) {
            $sql .= 'quantidade,';
            $args[] = $estoquemovimento->getQuantidade();
        }
        if (strlen($estoquemovimento->getCompraid()) > 0) {
            $sql .= 'compraid,';
            $args[] = $estoquemovimento->getCompraid();
        }
        if (strlen($estoquemovimento->getValorunitario()) > 0) {
            $sql .= 'valorunitario,';
            $args[] = $estoquemovimento->getValorunitario();
        }
        if (strlen($estoquemovimento->getEstoquemovimentodata()) > 0) {
            $sql .= 'data,';
            $args[] = $estoquemovimento->getEstoquemovimentodata();
        }

        $sql = substr($sql, 0, strlen($sql) - 1) . ') VALUES (';
        for ($i = 0; $i < count($args); $i++) {
            $sql .= '?,';
        }

        $sql = substr($sql, 0, strlen($sql) - 1) . ')';

        $result = Transaction::runPrepare($sql, $args);
        return $result->rowCount();
    }

    public static function updateEstoquemovimento($estoquemovimento) {

        $args = array();

        $sql = 'update estoquemovimento set ';

        if (isset($estoquemovimento->EM_produtoid)) {
            $sql .= 'produtoid = ?, ';
            $args[] = $estoquemovimento->getEM_produtoid();
        }
        if (isset($estoquemovimento->requisicaoid)) {
            $sql .= 'requisicaoid = ?, ';
            $args[] = $estoquemovimento->getRequisicaoid();
        }
        if (isset($estoquemovimento->operacao)) {
            $sql .= 'operacao = ?, ';
            $args[] = $estoquemovimento->getOperacao();
        }
        if (isset($estoquemovimento->quantidade)) {
            $sql .= 'quantidade = ?, ';
            $args[] = $estoquemovimento->getQuantidade();
        }
        if (isset($estoquemovimento->compraid)) {
            $sql .= 'compraid = ?, ';
            $args[] = $estoquemovimento->getCompraid();
        }
        if (isset($estoquemovimento->valorunitario)) {
            $sql .= 'valorunitario = ?, ';
            $args[] = $estoquemovimento->getValorunitario();
        }
        if (isset($estoquemovimento->estoquemovimentodata)) {
            $sql .= 'data = ?, ';
            $args[] = $estoquemovimento->getEstoquemovimentodata();
        }

        $args[] = $estoquemovimento->getEstoquemovimentoid();

        for ($i = 0; $i < count($args); $i++) {
            if (strlen($args[$i]) == 0) {
                $args[$i] = null; // Aqui faz o elemento vazio ficar NULL
            }
        }

        $sql = substr($sql, 0, strlen($sql) - 2) . ' where estoquemovimentoid = ?';

        return Transaction::runPrepare($sql, $args);
    }

    public static function deleteEstoquemovimento($estoquemovimento) {

        $sql = "DELETE FROM estoquemovimento WHERE estoquemovimentoid='{$estoquemovimento->getEstoquemovimentoid()}'";

        return Transaction::runExecute($sql);
    }

    public static function listEstoquemovimento($datainicial = '01-01-2010', $datafinal = '30-12-2100') {

        $sql = "     SELECT EM.estoquemovimentoid,
                            EM.produtoid,
                            P.nome AS produtonome,
                            P.und,
                            EM.requisicaoid,
                            U.nome AS usuarionome,
                            EM.operacao,
                            CASE EM.operacao
                                 WHEN 1 THEN 'Entrada'
                                 WHEN 2 THEN 'Saída'
                            END AS operacaonome,
                            EM.quantidade,
                            EM.valorunitario,
                            EM.data,
                            EM.compraid,
                            F.fantazia AS fantazia
                       FROM estoquemovimento EM
                 INNER JOIN produto P
                         ON P.produtoid = EM.produtoid
                  LEFT JOIN requisicao R
                         ON R.requisicaoid = EM.requisicaoid
                        AND R.situacao = 3
                  LEFT JOIN usuario U
                         ON U.usuarioid = R.usuarioid
                  LEFT JOIN compra C
                         ON C.compraid = EM.compraid
                        AND C.situacao = 3
                  LEFT JOIN fornecedor F
                         ON F.fornecedorid = C.fornecedorid
                      WHERE EM.operacao <> 0
                        AND EM.data BETWEEN '{$datainicial}' AND '{$datafinal}'
                   ORDER BY EM.data";

        $result = Transaction::runExecute($sql);

        $arrayEstoquemovimento = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $estoquemovimento = new estoquemovimentoModel();
            $estoquemovimento->setEstoquemovimentoid($row['estoquemovimentoid']);
            $estoquemovimento->setProdutoid($row['produtoid']);
            $estoquemovimento->setProdutonome($row['produtonome']);
            $estoquemovimento->setUnd($row['und']);
            $estoquemovimento->setRequisicaoid($row['requisicaoid']);
            $estoquemovimento->setUsuarionome($row['usuarionome']);
            $estoquemovimento->setOperacao($row['operacao']);
            $estoquemovimento->setOperacaonome($row['operacaonome']);
            $estoquemovimento->setQuantidade($row['quantidade']);
            $estoquemovimento->setValorunitario($row['valorunitario']);
            $estoquemovimento->setEstoquemovimentodata($row['data']);
            $estoquemovimento->setCompraid($row['compraid']);
            $estoquemovimento->setFantazia($row['fantazia']);
            array_push($arrayEstoquemovimento, $estoquemovimento);
        }

        return $arrayEstoquemovimento;
    }

    public static function listEstoquemovimentocompra($estoquemovimento) {

        $sql = "     SELECT EM.estoquemovimentoid,
                            P.produtoid,
                            P.nome AS produtonome,
                            P.und,
                            p.estoqueatual,
                            p.estoqueminimo,
                            p.estoquemaximo,
                            EM.requisicaoid,
                            EM.compraid,
                            EM.operacao,
                            EM.quantidade,
                            EM.valorunitario,
                            C.situacao
                       FROM estoquemovimento EM
                 INNER JOIN produto P
                         ON P.produtoid = EM.produtoid
                 INNER JOIN compra C
                         ON C.compraid = EM.compraid
                      WHERE EM.compraid = {$estoquemovimento->getCompraid()}";

        $result = Transaction::runExecute($sql);

        $arrayEstoquemovimento = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $estoquemovimento = new estoquemovimentoModel();
            $estoquemovimento->setEstoquemovimentoid($row['estoquemovimentoid']);
            $estoquemovimento->setProdutoid($row['produtoid']);
            $estoquemovimento->setProdutonome($row['produtonome']);
            $estoquemovimento->setUnd($row['und']);
            $estoquemovimento->setEstoqueatual($row['estoqueatual']);
            $estoquemovimento->setEstoqueminimo($row['estoqueminimo']);
            $estoquemovimento->setEstoquemaximo($row['estoquemaximo']);
            $estoquemovimento->setRequisicaoid($row['requisicaoid']);
            $estoquemovimento->setCompraid($row['compraid']);
            $estoquemovimento->setOperacao($row['operacao']);
            $estoquemovimento->setQuantidade($row['quantidade']);
            $estoquemovimento->setValorunitario($row['valorunitario']);
            $estoquemovimento->setComprasituacao($row['situacao']);
            array_push($arrayEstoquemovimento, $estoquemovimento);
        }

        return $arrayEstoquemovimento;
    }

    public static function listEstoquemovimentorequisicao($estoquemovimento) {

        $sql = "     SELECT EM.estoquemovimentoid,
                            P.produtoid,
                            P.nome AS produtonome,
                            P.und,
                            p.estoqueatual,
                            p.estoqueminimo,
                            p.estoquemaximo,
                            EM.requisicaoid,
                            EM.compraid,
                            EM.operacao,
                            EM.quantidade,
                            EM.valorunitario,
                            R.situacao
                       FROM estoquemovimento EM
                 INNER JOIN produto P
                         ON P.produtoid = EM.produtoid
                 INNER JOIN requisicao R
                         ON R.requisicaoid = EM.requisicaoid
                      WHERE EM.requisicaoid = {$estoquemovimento->getRequisicaoid()}";

        $result = Transaction::runExecute($sql);

        $arrayEstoquemovimento = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $estoquemovimento = new estoquemovimentoModel();
            $estoquemovimento->setEstoquemovimentoid($row['estoquemovimentoid']);
            $estoquemovimento->setProdutoid($row['produtoid']);
            $estoquemovimento->setProdutonome($row['produtonome']);
            $estoquemovimento->setUnd($row['und']);
            $estoquemovimento->setEstoqueatual($row['estoqueatual']);
            $estoquemovimento->setEstoqueminimo($row['estoqueminimo']);
            $estoquemovimento->setEstoquemaximo($row['estoquemaximo']);
            $estoquemovimento->setRequisicaoid($row['requisicaoid']);
            $estoquemovimento->setCompraid($row['compraid']);
            $estoquemovimento->setOperacao($row['operacao']);
            $estoquemovimento->setQuantidade($row['quantidade']);
            $estoquemovimento->setValorunitario($row['valorunitario']);
            $estoquemovimento->setRequisicaosituacao($row['situacao']);
            array_push($arrayEstoquemovimento, $estoquemovimento);
        }

        return $arrayEstoquemovimento;
    }

    public static function getEstoquemovimento($estoquemovimento) {

        $sql = "     SELECT EM.estoquemovimentoid,
                            EM.produtoid,
                            P.nome AS produtonome,
                            P.und,
                            P.estoqueminimo,
                            P.estoquemaximo,
                            EM.requisicaoid,
                            U.nome AS usuarionome,
                            EM.operacao,
                            CASE EM.operacao
                                 WHEN 1 THEN 'Entrada'
                                 WHEN 2 THEN 'Saída'
                            END AS operacaonome,
                            EM.quantidade,
                            EM.valorunitario,
                            EM.data,
                            EM.compraid,
                            F.fantazia AS fantazia,
                            estoqueatual(P.produtoid) AS estoqueatual
                       FROM estoquemovimento EM
                 INNER JOIN produto P
                         ON P.produtoid = EM.produtoid
                  LEFT JOIN requisicao R
                         ON R.requisicaoid = EM.requisicaoid
                        AND R.situacao = 3
                  LEFT JOIN usuario U
                         ON U.usuarioid = R.usuarioid
                  LEFT JOIN compra C
                         ON C.compraid = EM.compraid
                        AND C.situacao = 3
                  LEFT JOIN fornecedor F
                         ON F.fornecedorid = C.fornecedorid
                      WHERE EM.operacao <> 0
                        AND EM.estoquemovimentoid='{$estoquemovimento->getEstoquemovimentoid()}'";

        $result = Transaction::runExecute($sql);

        $estoquemovimento = new estoquemovimentoModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $estoquemovimento->setEstoquemovimentoid($row['estoquemovimentoid']);
            $estoquemovimento->setProdutoid($row['produtoid']);
            $estoquemovimento->setProdutonome($row['produtonome']);
            $estoquemovimento->setUnd($row['und']);
            $estoquemovimento->setEstoqueminimo($row['estoqueminimo']);
            $estoquemovimento->setEstoquemaximo($row['estoquemaximo']);
            $estoquemovimento->setRequisicaoid($row['requisicaoid']);
            $estoquemovimento->setUsuarionome($row['usuarionome']);
            $estoquemovimento->setOperacao($row['operacao']);
            $estoquemovimento->setOperacaonome($row['operacaonome']);
            $estoquemovimento->setQuantidade($row['quantidade']);
            $estoquemovimento->setValorunitario($row['valorunitario']);
            $estoquemovimento->setEstoquemovimentodata($row['data']);
            $estoquemovimento->setCompraid($row['compraid']);
            $estoquemovimento->setFantazia($row['fantazia']);
            $estoquemovimento->setEstoqueatual($row['estoqueatual']);
        }

        return $estoquemovimento;
    }

    public static function getEstoquemovimentocompra($estoquemovimento) {

        $sql = "     SELECT E.estoquemovimentoid,
                            E.produtoid,
                            E.requisicaoid,
                            E.compraid,
                            E.operacao,
                            E.quantidade,
                            E.data
                       from estoquemovimento E
                 inner join compra C
                         on C.compraid = E.compraid
                        and C.compraid = '{$estoquemovimento->getCompraid()}'
                 inner join produto P
                         on P.produtoid = E.produtoid
                        and P.produtoid = '{$estoquemovimento->getEM_produtoid()}'";

        $result = Transaction::runExecute($sql);

        $estoquemovimento = new estoquemovimentoModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $estoquemovimento = new estoquemovimentoModel();
            $estoquemovimento->setEstoquemovimentoid($row['estoquemovimentoid']);
            $estoquemovimento->setProdutoid($row['produtoid']);
            $estoquemovimento->setRequisicaoid($row['requisicaoid']);
            $estoquemovimento->setCompraid($row['compraid']);
            $estoquemovimento->setOperacao($row['operacao']);
            $estoquemovimento->setQuantidade($row['quantidade']);
            $estoquemovimento->setEstoquemovimentodata($row['data']);
        }

        return $estoquemovimento;
    }

    public static function getEstoquemovimentorequisicao($estoquemovimento) {

        $sql = "     SELECT E.estoquemovimentoid,
                            E.produtoid,
                            E.requisicaoid,
                            E.compraid,
                            E.operacao,
                            E.quantidade,
                            E.data
                       FROM estoquemovimento E
                 inner join requisicao R
                         on R.requisicaoid = E.requisicaoid
                        and R.requisicaoid = '{$estoquemovimento->getRequisicaoid()}'
                 inner join produto P
                         on P.produtoid = E.produtoid
                        and P.produtoid = '{$estoquemovimento->getEM_produtoid()}'";

        $result = Transaction::runExecute($sql);

        $estoquemovimento = new estoquemovimentoModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $estoquemovimento = new estoquemovimentoModel();
            $estoquemovimento->setEstoquemovimentoid($row['estoquemovimentoid']);
            $estoquemovimento->setProdutoid($row['produtoid']);
            $estoquemovimento->setRequisicaoid($row['requisicaoid']);
            $estoquemovimento->setCompraid($row['compraid']);
            $estoquemovimento->setOperacao($row['operacao']);
            $estoquemovimento->setQuantidade($row['quantidade']);
            $estoquemovimento->setEstoquemovimentodata($row['data']);
        }

        return $estoquemovimento;
    }

    public static function searchEstoquemovimento($filters) {

        $sql = "     SELECT EM.estoquemovimentoid,
                            EM.produtoid,
                            P.nome AS produtonome,
                            P.und,
                            EM.requisicaoid,
                            U.nome AS usuarionome,
                            EM.operacao,
                            CASE EM.operacao
                                 WHEN 1 THEN 'Entrada'
                                 WHEN 2 THEN 'Saída'
                            END AS operacaonome,
                            EM.quantidade,
                            EM.valorunitario,
                            EM.data,
                            EM.compraid,
                            F.fantazia AS fantazia
                       FROM estoquemovimento EM
                 INNER JOIN produto P
                         ON P.produtoid = EM.produtoid
                  LEFT JOIN requisicao R
                         ON R.requisicaoid = EM.requisicaoid
                        AND R.situacao = 3
                  LEFT JOIN usuario U
                         ON U.usuarioid = R.usuarioid
                  LEFT JOIN compra C
                         ON C.compraid = EM.compraid
                        AND C.situacao = 3
                  LEFT JOIN fornecedor F
                         ON F.fornecedorid = C.fornecedorid";
        
        $where = "";
        $args = array();

        if (strlen($filters->datainicial) > 0 && strlen($filters->datafinal) > 0) {

            $where .= ' AND EM.data BETWEEN ? AND ? ';
            $args[] = $filters->datainicial;
            $args[] = $filters->datafinal;
        }

        if (strlen($filters->usuarioid) > 0) {

            $where .= ' AND U.usuarioid = ? ';
            $args[] = $filters->usuarioid;
        }

        if (strlen($filters->fornecedorid) > 0) {

            $where .= ' AND F.fornecedorid = ? ';
            $args[] = $filters->fornecedorid;
        }
        
        if (strlen($filters->produtoid) > 0) {

            $where .= ' AND P.produtoid = ? ';
            $args[] = $filters->produtoid;
        }

        if (is_array($filters->operacao) && count($filters->operacao) > 0) {

            $ofs = implode(',', $filters->operacao);
            $where .= " AND EM.operacao IN ( {$ofs} )";
        }else{
            
            $where .= " AND EM.operacao <> 0";
        }

        if (strlen($where) > 0) {
            $sql .= ' WHERE ' . substr($where, 4);
        }

        $sql .= ' ORDER BY EM.data';

        $result = Transaction::runPrepare($sql, $args);

        $arrayEstoquemovimento = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $estoquemovimento = new estoquemovimentoModel();
            $estoquemovimento->setEstoquemovimentoid($row['estoquemovimentoid']);
            $estoquemovimento->setProdutoid($row['produtoid']);
            $estoquemovimento->setProdutonome($row['produtonome']);
            $estoquemovimento->setUnd($row['und']);
            $estoquemovimento->setRequisicaoid($row['requisicaoid']);
            $estoquemovimento->setUsuarionome($row['usuarionome']);
            $estoquemovimento->setOperacao($row['operacao']);
            $estoquemovimento->setOperacaonome($row['operacaonome']);
            $estoquemovimento->setQuantidade($row['quantidade']);
            $estoquemovimento->setValorunitario($row['valorunitario']);
            $estoquemovimento->setEstoquemovimentodata($row['data']);
            $estoquemovimento->setCompraid($row['compraid']);
            $estoquemovimento->setFantazia($row['fantazia']);
            array_push($arrayEstoquemovimento, $estoquemovimento);
        }

        return $arrayEstoquemovimento;
    }

    public static function searchEstoquemovimentoTotal($filters) {

        $sql = "    SELECT DISTINCT P.produtoid,
                            P.nome AS produtonome,
                            P.und,

                            sum((select EM2.quantidade
                               from estoquemovimento EM2
                              where EM2.estoquemovimentoid = EM.estoquemovimentoid
                                and EM2.operacao = 1)) as entrada,

                            sum((select EM2.quantidade
                               from estoquemovimento EM2
                              where EM2.estoquemovimentoid = EM.estoquemovimentoid
                                and EM2.operacao = 2)) as saida
                                
                       FROM produto P
                 INNER JOIN estoquemovimento EM
                         ON P.produtoid = EM.produtoid
                  LEFT JOIN requisicao R
                         ON R.requisicaoid = EM.requisicaoid
                        AND R.situacao = 3
                  LEFT JOIN usuario U
                         ON U.usuarioid = R.usuarioid
                  LEFT JOIN compra C
                         ON C.compraid = EM.compraid
                        AND C.situacao = 3
                  LEFT JOIN fornecedor F
                         ON F.fornecedorid = C.fornecedorid";
        
        $where = "";
        $args = array();

        if (strlen($filters->datainicial) > 0 && strlen($filters->datafinal) > 0) {

            $where .= ' AND EM.data BETWEEN ? AND ? ';
            $args[] = $filters->datainicial;
            $args[] = $filters->datafinal;
        }

        if (strlen($filters->usuarioid) > 0) {

            $where .= ' AND U.usuarioid = ? ';
            $args[] = $filters->usuarioid;
        }

        if (strlen($filters->fornecedorid) > 0) {

            $where .= ' AND F.fornecedorid = ? ';
            $args[] = $filters->fornecedorid;
        }
        
        if (strlen($filters->produtoid) > 0) {

            $where .= ' AND P.produtoid = ? ';
            $args[] = $filters->produtoid;
        }

        if (is_array($filters->operacao) && count($filters->operacao) > 0) {

            $ofs = implode(',', $filters->operacao);
            $where .= " AND EM.operacao IN ( {$ofs} )";
        }else{
            
            $where .= " AND EM.operacao <> 0";
        }

        if (strlen($where) > 0) {
            $sql .= ' WHERE ' . substr($where, 4);
        }

        $sql .= ' group by P.produtoid';

        $result = Transaction::runPrepare($sql, $args);

        $arrayEstoquemovimento = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $estoquemovimento = new estoquemovimentoModel();
            $estoquemovimento->setProdutoid($row['produtoid']);
            $estoquemovimento->setProdutonome($row['produtonome']);
            $estoquemovimento->setUnd($row['und']);
            $estoquemovimento->setTotalentrada($row['entrada']);
            $estoquemovimento->setTotalsaida($row['saida']);
            array_push($arrayEstoquemovimento, $estoquemovimento);
        }

        return $arrayEstoquemovimento;
    }

}

?>