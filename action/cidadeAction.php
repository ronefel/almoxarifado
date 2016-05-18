<?php

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/cidadeModel.php';

class cidadeAction extends cidadeModel {

    public static function insertCidade($cidade) {

        $sql = "insert into cidade (
                            nome,
                            uf,
                            cep)
                   values ('{$cidade->getNome()}',
                           '{$cidade->getUf()}',
                           '{$cidade->getCep()}')";

        return Transaction::runExecute($sql);
    }

    public static function updateCidade($cidade) {

        $sql = "update cidade
                   set nome='{$cidade->getNome()}',
                       uf='{$cidade->getUf()}',
                       cep='{$cidade->getCep()}'
                 where cidadeid='{$cidade->getCidadeid()}'";

        return Transaction::runExecute($sql);
    }

    public static function deleteCidade($cidade) {

        $sql = "DELETE FROM cidade WHERE cidadeid='{$cidade->getCidadeid()}'";

        $result = Transaction::runExecute($sql);
        if ($result) {
            echo 'sucesso';
        } else {

            $total = "";
            $sql1 = "    SELECT count(c.cidadeid) as total
                          FROM cidade c
                    INNER JOIN fornecedor f
                            ON f.cidadeid = c.cidadeid
                         WHERE c.cidadeid = '{$cidade->getCidadeid()}'";

            $result1 = Transaction::runExecute($sql1);

            if ($result1) {
                while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
                    $total = $row['total'];
                }

                echo 'Não é possível excluir essa cidade, essa cidade está vinculada a ' . $total . ' fornecedores.';
            }else{
                echo 'Ouve um erro na hora de excluir a cidade.';
            }
        }
    }

    public static function listCidade() {

        $sql = "     SELECT cidadeid,
                            nome,
                            uf,
                            cep
                       FROM cidade
                   ORDER BY nome";

        $result = Transaction::runExecute($sql);

        $arrayCidade = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $cidade = new cidadeModel();
            $cidade->setCidadeid($row['cidadeid']);
            $cidade->setNome($row['nome']);
            $cidade->setUf($row['uf']);
            $cidade->setCep($row['cep']);
            array_push($arrayCidade, $cidade);
        }

        return $arrayCidade;
    }

    public static function getCidade($cidade) {

        $sql = "     SELECT cidadeid,
                            nome,
                            uf,
                            cep
                       FROM cidade
                      WHERE cidadeid='{$cidade->getCidadeid()}'";

        $result = Transaction::runExecute($sql);

        $cidade = new cidadeModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $cidade = new cidadeModel();
            $cidade->setCidadeid($row['cidadeid']);
            $cidade->setNome($row['nome']);
            $cidade->setUf($row['uf']);
            $cidade->setCep($row['cep']);
        }

        return $cidade;
    }

    public static function isExists($cidade) {

        $sql = "SELECT * 
                  FROM cidade
                  WHERE (cep ILIKE '{$cidade->getCep()}'
                     OR nome ILIKE '{$cidade->getNome()}')";

        $result = Transaction::runExecute($sql);

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            return true;
        }
        return false;
    }

}

?>