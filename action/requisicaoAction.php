<?php

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/requisicaoModel.php';

class requisicaoAction extends requisicaoModel {

    public static function insertRequisicao($requisicao) {

        $args = array();

        $sql = "insert into requisicao (";

        if (strlen($requisicao->getUsuarioid()) > 0) {
            $sql .= 'usuarioid,';
            $args[] = $requisicao->getUsuarioid();
        }
        if (strlen($requisicao->getRequisicaoemissao()) > 0) {
            $sql .= 'emissao,';
            $args[] = $requisicao->getRequisicaoemissao();
        }
        if (strlen($requisicao->getRequisicaoaprovacao()) > 0) {
            $sql .= 'aprovacao,';
            $args[] = $requisicao->getRequisicaoaprovacao();
        }
        if (strlen($requisicao->getRequisicaoentrega()) > 0) {
            $sql .= 'entrega,';
            $args[] = $requisicao->getRequisicaoentrega();
        }
        if (strlen($requisicao->getRequisicaosituacao()) > 0) {
            $sql .= 'situacao,';
            $args[] = $requisicao->getRequisicaosituacao();
        }

        $sql = substr($sql, 0, strlen($sql) - 1) . ') VALUES (';
        for ($i = 0; $i < count($args); $i++) {
            $sql .= '?,';
        }

        $sql = substr($sql, 0, strlen($sql) - 1) . ') returning requisicaoid';

        $result = Transaction::runPrepare($sql, $args);

        $id = $result->fetch(PDO::FETCH_OBJ);

        return $id->requisicaoid;
    }

    public static function updateRequisicao($requisicao) {

        $args = array();

        $sql = 'update requisicao set ';

        if (isset($requisicao->usuarioid)) {
            $sql .= 'usuarioid = ?, ';
            $args[] = $requisicao->getUsuarioid();
        }
        if (isset($requisicao->emissao)) {
            $sql .= 'emissao = ?, ';
            $args[] = $requisicao->getRequisicaoemissao();
        }
        if (isset($requisicao->aprovacao)) {
            $sql .= 'aprovacao = ?, ';
            $args[] = $requisicao->getRequisicaoaprovacao();
        }
        if (isset($requisicao->entrega)) {
            $sql .= 'entrega = ?, ';
            $args[] = $requisicao->getRequisicaoentrega();
        }
        if (isset($requisicao->situacao)) {
            $sql .= 'situacao = ?, ';
            $args[] = $requisicao->getRequisicaosituacao();
        }
        if (isset($requisicao->reprovacaotxt)) {
            $sql .= 'reprovacaotxt = ?, ';
            $args[] = $requisicao->getRequisicaoreprovacaotxt();
        }
        if (isset($requisicao->reprovacao)) {
            $sql .= 'reprovacao = ?, ';
            $args[] = $requisicao->getRequisicaoreprovacao();
        }

        $args[] = $requisicao->getRequisicaoid();

        for ($i = 0; $i < count($args); $i++) {
            if (strlen($args[$i]) == 0) {
                $args[$i] = null; // Aqui faz o elemento vazio ficar NULL
            }
        }

        $sql = substr($sql, 0, strlen($sql) - 2) . ' where requisicaoid = ?';

        return Transaction::runPrepare($sql, $args);
    }

    public static function deleteRequisicao($requisicao) {

        $sql = "DELETE FROM requisicao WHERE requisicaoid='{$requisicao->getRequisicaoid()}'";

        return Transaction::runExecute($sql);
    }

    public static function listRequisicao($datainicial = '01-01-2010', $datafinal = '30-12-2100') {

        $sql = "     SELECT C.requisicaoid,
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
                            U.usuarioid,
                            U.nome AS usuarionome,
                            D.departamentoid,
                            D.nome AS partamentonome
                       FROM requisicao C
                 INNER JOIN usuario U
                         ON U.usuarioid = C.usuarioid
                 INNER JOIN departamento D
                         ON D.departamentoid = U.departamentoid
                      WHERE C.emissao BETWEEN '{$datainicial}' AND '{$datafinal}'
                   ORDER BY C.situacao ASC, C.emissao ASC";

        $result = Transaction::runExecute($sql);

        $arrayRequisicao = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $requisicao = new requisicaoModel();
            $requisicao->setRequisicaoid($row['requisicaoid']);
            $requisicao->setRequisicaoemissao($row['emissao']);
            $requisicao->setRequisicaoaprovacao($row['aprovacao']);
            $requisicao->setRequisicaoentrega($row['entrega']);
            $requisicao->setRequisicaosituacao($row['situacao']);
            $requisicao->setRequisicaosituacaonome($row['situacaonome']);
            $requisicao->setUsuarioid($row['usuarioid']);
            $requisicao->setUsuarionome($row['usuarionome']);
            $requisicao->setDepartamentoid($row['departamentoid']);
            $requisicao->setDepartamentonome($row['partamentonome']);
            $requisicao->setRequisicaoreprovacaotxt($row['reprovacaotxt']);
            $requisicao->setRequisicaoreprovacao($row['reprovacao']);
            array_push($arrayRequisicao, $requisicao);
        }

        return $arrayRequisicao;
    }
    
    public static function listRequisicaousuario($datainicial = '2015-01-01', $datafinal = '2200-12-30', $usuario) {

        $sql = "     SELECT C.requisicaoid,
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
                            U.usuarioid,
                            U.nome AS usuarionome,
                            D.departamentoid,
                            D.nome AS partamentonome
                       FROM requisicao C
                 INNER JOIN usuario U
                         ON U.usuarioid = C.usuarioid
                 INNER JOIN departamento D
                         ON D.departamentoid = U.departamentoid
                      WHERE C.emissao BETWEEN '{$datainicial}' AND '{$datafinal}'
                        AND U.usuarioid = {$usuario->getUsuarioid()}
                   ORDER BY C.situacao ASC, C.emissao ASC";

        $result = Transaction::runExecute($sql);

        $arrayRequisicao = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $requisicao = new requisicaoModel();
            $requisicao->setRequisicaoid($row['requisicaoid']);
            $requisicao->setRequisicaoemissao($row['emissao']);
            $requisicao->setRequisicaoaprovacao($row['aprovacao']);
            $requisicao->setRequisicaoentrega($row['entrega']);
            $requisicao->setRequisicaosituacao($row['situacao']);
            $requisicao->setRequisicaosituacaonome($row['situacaonome']);
            $requisicao->setUsuarioid($row['usuarioid']);
            $requisicao->setUsuarionome($row['usuarionome']);
            $requisicao->setDepartamentoid($row['departamentoid']);
            $requisicao->setDepartamentonome($row['partamentonome']);
            $requisicao->setRequisicaoreprovacaotxt($row['reprovacaotxt']);
            $requisicao->setRequisicaoreprovacao($row['reprovacao']);
            array_push($arrayRequisicao, $requisicao);
        }

        return $arrayRequisicao;
    }

    public static function getRequisicao($requisicao) {

        $sql = "     SELECT C.requisicaoid,
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
                            F.usuarioid,
                            F.nome AS usuarionome,
                            D.departamentoid,
                            D.nome AS departamentonome,
                            L.localid,
                            L.nome AS localnome
                       FROM requisicao C
                 INNER JOIN usuario F
                         ON F.usuarioid = C.usuarioid
                 INNER JOIN departamento D
                         ON D.departamentoid = F.departamentoid
                 INNER JOIN local L
                         ON L.localid = D.localid
                      WHERE requisicaoid='{$requisicao->getRequisicaoid()}'";

        $result = Transaction::runExecute($sql);

        $requisicao = new requisicaoModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $requisicao = new requisicaoModel();
            $requisicao->setRequisicaoid($row['requisicaoid']);
            $requisicao->setRequisicaoemissao($row['emissao']);
            $requisicao->setRequisicaoaprovacao($row['aprovacao']);
            $requisicao->setRequisicaoentrega($row['entrega']);
            $requisicao->setRequisicaosituacao($row['situacao']);
            $requisicao->setRequisicaosituacaonome($row['situacaonome']);
            $requisicao->setUsuarioid($row['usuarioid']);
            $requisicao->setUsuarionome($row['usuarionome']);
            $requisicao->setDepartamentoid($row['departamentoid']);
            $requisicao->setDepartamentonome($row['departamentonome']);
            $requisicao->setLocalid($row['localid']);
            $requisicao->setLocalnome($row['localnome']);
            $requisicao->setRequisicaoreprovacaotxt($row['reprovacaotxt']);
            $requisicao->setRequisicaoreprovacao($row['reprovacao']);
        }

        return $requisicao;
    }

    public static function searchRequisicao($filters) {

        $sql = "     SELECT C.requisicaoid,
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
                            U.usuarioid,
                            U.nome AS usuarionome,
                            D.departamentoid,
                            D.nome AS partamentonome,
                            requisicaovalortotal(C.requisicaoid) AS requisicaovalortotal
                       FROM requisicao C
                 INNER JOIN usuario U
                         ON U.usuarioid = C.usuarioid
                 INNER JOIN departamento D
                         ON D.departamentoid = U.departamentoid";
        
        $where = "";
        $args = array();

        if (strlen($filters->datainicial) > 0 && strlen($filters->datafinal) > 0) {

            $where .= ' AND C.emissao BETWEEN ? AND ? ';
            $args[] = $filters->datainicial;
            $args[] = $filters->datafinal;
        }

        if (strlen($filters->requisicaoid) > 0) {

            $where .= ' AND C.requisicaoid = ? ';
            $args[] = $filters->requisicaoid;
        }

        if (strlen($filters->usuarioid) > 0) {

            $where .= ' AND U.usuarioid = ? ';
            $args[] = $filters->usuarioid;
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

        $arrayRequisicao = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $requisicao = new requisicaoModel();
            $requisicao->setRequisicaoid($row['requisicaoid']);
            $requisicao->setRequisicaoemissao($row['emissao']);
            $requisicao->setRequisicaoaprovacao($row['aprovacao']);
            $requisicao->setRequisicaoentrega($row['entrega']);
            $requisicao->setRequisicaosituacao($row['situacao']);
            $requisicao->setRequisicaosituacaonome($row['situacaonome']);
            $requisicao->setUsuarioid($row['usuarioid']);
            $requisicao->setUsuarionome($row['usuarionome']);
            $requisicao->setDepartamentoid($row['departamentoid']);
            $requisicao->setDepartamentonome($row['partamentonome']);
            $requisicao->setRequisicaoreprovacaotxt($row['reprovacaotxt']);
            $requisicao->setRequisicaoreprovacao($row['reprovacao']);
            $requisicao->setRequisicaovalortotal($row['requisicaovalortotal']);
            array_push($arrayRequisicao, $requisicao);
        }

        return $arrayRequisicao;
    }

}

?>