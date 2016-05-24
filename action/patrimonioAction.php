<?php

header('Content-type: text/html; charset=UTF-8', true);
require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/patrimonioModel.php';

class patrimonioAction extends patrimonioModel {

    public static function insertPatrimonio($patrimonio) {

        $args = array();

        $sql = "insert into patrimonio (";

        if (strlen($patrimonio->getPatrimonioid()) > 0) {
            $sql .= 'patrimonioid,';
            $args[] = $patrimonio->getPatrimonioid();
        }
        if (strlen($patrimonio->getCategoriaid()) > 0) {
            $sql .= 'categoriaid,';
            $args[] = $patrimonio->getCategoriaid();
        }
        if (strlen($patrimonio->getFornecedorid()) > 0) {
            $sql .= 'fornecedorid,';
            $args[] = $patrimonio->getFornecedorid();
        }
        if (strlen($patrimonio->getPatrimoniodescricao()) > 0) {
            $sql .= 'descricao,';
            $args[] = $patrimonio->getPatrimoniodescricao();
        }
        if (strlen($patrimonio->getSerie()) > 0) {
            $sql .= 'serie,';
            $args[] = $patrimonio->getSerie();
        }
        if (strlen($patrimonio->getValor()) > 0) {
            $sql .= 'valor,';
            $args[] = $patrimonio->getValor();
        }
        if (strlen($patrimonio->getMarcaid()) > 0) {
            $sql .= 'marcaid,';
            $args[] = $patrimonio->getMarcaid();
        }
        if (strlen($patrimonio->getDatacompra()) > 0) {
            $sql .= 'datacompra,';
            $args[] = $patrimonio->getDatacompra();
        }
        if (strlen($patrimonio->getNotafiscal()) > 0) {
            $sql .= 'notafiscal,';
            $args[] = $patrimonio->getNotafiscal();
        }
        if (strlen($patrimonio->getFimgarantia()) > 0) {
            $sql .= 'fimgarantia,';
            $args[] = $patrimonio->getFimgarantia();
        }
        if (strlen($patrimonio->getDataimplantacao()) > 0) {
            $sql .= 'dataimplantacao,';
            $args[] = $patrimonio->getDataimplantacao();
        }
        if (strlen($patrimonio->getEstadoconservacao()) > 0) {
            $sql .= 'estadoconservacao,';
            $args[] = $patrimonio->getEstadoconservacao();
        }
        if (strlen($patrimonio->getObs()) > 0) {
            $sql .= 'obs,';
            $args[] = $patrimonio->getObs();
        }
        if (strlen($patrimonio->getDepartamentoid()) > 0) {
            $sql .= 'departamentoid,';
            $args[] = $patrimonio->getDepartamentoid();
        }

        $sql = substr($sql, 0, strlen($sql) - 1) . ') VALUES (';
        for ($i = 0; $i < count($args); $i++) {
            $sql .= '?,';
        }

        $sql = substr($sql, 0, strlen($sql) - 1) . ')';

        return Transaction::runPrepare($sql, $args);
    }

    public static function updatePatrimonio($patrimonio) {

        $args = array();

        $sql = 'update patrimonio set ';

        if ($patrimonio->getCategoriaid() != null) {
            $sql .= 'categoriaid = ?, ';
            $args[] = $patrimonio->getCategoriaid();
        }
        if ($patrimonio->getFornecedorid() != null) {
            $sql .= 'fornecedorid = ?, ';
            $args[] = $patrimonio->getFornecedorid();
        }
        if (isset($patrimonio->patrimoniodescricao)) {
            $sql .= 'descricao = ?, ';
            $args[] = $patrimonio->getPatrimoniodescricao();
        }
        if (isset($patrimonio->serie)) {
            $sql .= 'serie = ?, ';
            $args[] = $patrimonio->getSerie();
        }
        if (isset($patrimonio->valor)) {
            $sql .= 'valor = ?, ';
            $args[] = $patrimonio->getValor();
        }
        if ($patrimonio->getMarcaid() != null) {
            $sql .= 'marcaid = ?, ';
            $args[] = $patrimonio->getMarcaid();
        }
        if (isset($patrimonio->datacompra)) {
            $sql .= 'datacompra = ?, ';
            $args[] = $patrimonio->getDatacompra();
        }
        if (isset($patrimonio->notafiscal)) {
            $sql .= 'notafiscal = ?, ';
            $args[] = $patrimonio->getNotafiscal();
        }
        if (isset($patrimonio->fimgarantia)) {
            $sql .= 'fimgarantia = ?, ';
            $args[] = $patrimonio->getFimgarantia();
        }
        if (isset($patrimonio->dataimplantacao)) {
            $sql .= 'dataimplantacao = ?, ';
            $args[] = $patrimonio->getDataimplantacao();
        }
        if (isset($patrimonio->estadoconservacao)) {
            $sql .= 'estadoconservacao = ?, ';
            $args[] = $patrimonio->getEstadoconservacao();
        }
        if (isset($patrimonio->obs)) {
            $sql .= 'obs = ?, ';
            $args[] = $patrimonio->getObs();
        }
        if ($patrimonio->getDepartamentoid() != null) {
            $sql .= 'departamentoid = ?, ';
            $args[] = $patrimonio->getDepartamentoid();
        }

        $args[] = $patrimonio->getPatrimonioid();

        for ($i = 0; $i < count($args); $i++) {
            if (strlen($args[$i]) == 0) {
                $args[$i] = null; // Aqui faz o elemento vazio ficar NULL
            }
        }

        $sql = substr($sql, 0, strlen($sql) - 2) . ' where patrimonioid = ?';

        return Transaction::runPrepare($sql, $args);
    }

    public static function deletePatrimonio($patrimonio) {

        $sql = "DELETE FROM patrimonio WHERE patrimonioid='{$patrimonio->getPatrimonioid()}'";

        $result = Transaction::runExecute($sql);
        if ($result) {
            echo 'sucesso';
        } else {

            echo 'Ouve um erro na hora de excluir o patrimonio.';
        }
    }

    public static function listPatrimonio() {

        $sql = "     SELECT P.patrimonioid,
                            P.categoriaid,
                            C.nome AS categorianome,
                            P.fornecedorid,
                            F.fantazia,
                            P.descricao,
                            P.serie,
                            P.valor,
                            P.marcaid,
                            M.nome AS marcanome,
                            P.datacompra,
                            P.notafiscal,
                            P.fimgarantia,
                            P.dataimplantacao,
                            P.estadoconservacao,
                            P.obs,
                            P.departamentoid,
                            D.nome AS departamentonome,
                            L.localid,
                            L.nome AS localnome
                       FROM patrimonio P
                  LEFT JOIN categoria C
                         ON C.categoriaid = P.categoriaid
                  LEFT JOIN fornecedor F
                         ON F.fornecedorid = P.fornecedorid
                  LEFT JOIN marca M
                         ON M.marcaid = P.marcaid
                  LEFT JOIN departamento D
                         ON D.departamentoid = P.departamentoid
                  LEFT JOIN local L
                         ON L.localid = D.localid
                   ORDER BY P.descricao";

        $result = Transaction::runExecute($sql);

        $arrayPatrimonio = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $patrimonio = new patrimonioModel();
            $patrimonio->setPatrimonioid($row['patrimonioid']);
            $patrimonio->setCategoriaid($row['categoriaid']);
            $patrimonio->setCategorianome($row['categorianome']);
            $patrimonio->setFornecedorid($row['fornecedorid']);
            $patrimonio->setFantazia($row['fantazia']);
            $patrimonio->setPatrimoniodescricao($row['descricao']);
            $patrimonio->setSerie($row['serie']);
            $patrimonio->setValor($row['valor']);
            $patrimonio->setMarcaid($row['marcaid']);
            $patrimonio->setMarcanome($row['marcanome']);
            $patrimonio->setDatacompra($row['datacompra']);
            $patrimonio->setNotafiscal($row['notafiscal']);
            $patrimonio->setFimgarantia($row['fimgarantia']);
            $patrimonio->setDataimplantacao($row['dataimplantacao']);
            $patrimonio->setEstadoconservacao($row['estadoconservacao']);
            $patrimonio->setObs($row['obs']);
            $patrimonio->setDepartamentoid($row['departamentoid']);
            $patrimonio->setDepartamentonome($row['departamentonome']);
            $patrimonio->setLocalid($row['localid']);
            $patrimonio->setLocalnome($row['localnome']);
            array_push($arrayPatrimonio, $patrimonio);
        }

        return $arrayPatrimonio;
    }

    public static function getPatrimonio($patrimonio) {

        $sql = "     SELECT P.patrimonioid,
                            P.categoriaid,
                            C.nome AS categorianome,
                            P.fornecedorid,
                            F.fantazia,
                            P.descricao,
                            P.serie,
                            P.valor,
                            P.marcaid,
                            M.nome AS marcanome,
                            P.datacompra,
                            P.notafiscal,
                            P.fimgarantia,
                            P.dataimplantacao,
                            P.estadoconservacao,
                            P.obs,
                            P.departamentoid,
                            D.nome AS departamentonome,
                            L.localid,
                            L.nome AS localnome
                       FROM patrimonio P
                  LEFT JOIN categoria C
                         ON C.categoriaid = P.categoriaid
                  LEFT JOIN fornecedor F
                         ON F.fornecedorid = P.fornecedorid
                  LEFT JOIN marca M
                         ON M.marcaid = P.marcaid
                  LEFT JOIN departamento D
                         ON D.departamentoid = P.departamentoid
                  LEFT JOIN local L
                         ON L.localid = D.localid
                      WHERE P.patrimonioid='{$patrimonio->getPatrimonioid()}'";

        $result = Transaction::runExecute($sql);

        $patrimonio = new patrimonioModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $patrimonio = new patrimonioModel();
            $patrimonio->setPatrimonioid($row['patrimonioid']);
            $patrimonio->setCategoriaid($row['categoriaid']);
            $patrimonio->setCategorianome($row['categorianome']);
            $patrimonio->setFornecedorid($row['fornecedorid']);
            $patrimonio->setFantazia($row['fantazia']);
            $patrimonio->setPatrimoniodescricao($row['descricao']);
            $patrimonio->setSerie($row['serie']);
            $patrimonio->setValor($row['valor']);
            $patrimonio->setMarcaid($row['marcaid']);
            $patrimonio->setMarcanome($row['marcanome']);
            $patrimonio->setDatacompra($row['datacompra']);
            $patrimonio->setNotafiscal($row['notafiscal']);
            $patrimonio->setFimgarantia($row['fimgarantia']);
            $patrimonio->setDataimplantacao($row['dataimplantacao']);
            $patrimonio->setEstadoconservacao($row['estadoconservacao']);
            $patrimonio->setObs($row['obs']);
            $patrimonio->setDepartamentoid($row['departamentoid']);
            $patrimonio->setDepartamentonome($row['departamentonome']);
            $patrimonio->setLocalid($row['localid']);
            $patrimonio->setLocalnome($row['localnome']);
        }

        return $patrimonio;
    }

}

?>