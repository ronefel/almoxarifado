<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/departamentoModel.php';

class departamentoAction extends departamentoModel {

    public static function insertDepartamento($departamento) {

        $sql = "insert into departamento (
                            localid,
                            nome)
                   values ('{$departamento->getLocalid()}',
                           '{$departamento->getDepartamentonome()}')";

        return Transaction::runExecute($sql);

    }

    public static function updateDepartamento($departamento) {

        $sql = "update departamento
                   set localid='{$departamento->getLocalid()}',
                       nome='{$departamento->getDepartamentonome()}'
                 where departamentoid='{$departamento->getDepartamentoid()}'";

        return Transaction::runExecute($sql);

    }

    public static function deleteDepartamento($departamento) {

        $sql = "DELETE FROM departamento WHERE departamentoid='{$departamento->getDepartamentoid()}'";

        $result = Transaction::runExecute($sql);
        if ($result) {
            echo 'sucesso';
        } else {

            $total = "";
            $sql1 = "    SELECT count(u.departamentoid) as total
                           FROM departamento d
                     INNER JOIN usuario u
                             ON u.departamentoid = d.departamentoid
                          WHERE d.departamentoid = '{$departamento->getDepartamentoid()}'";

            $result1 = Transaction::runExecute($sql1);

            if ($result1) {
                while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
                    $total = $row['total'];
                }

                echo 'Não é possível excluir esse departamento, '
                . 'esse departamento está vinculado a '
                . $total . ' usuário/requisitante.';
            } else {
                echo 'Ouve um erro na hora de excluir o departamento.';
            }
        }

    }

    public static function listDepartamento() {

        $sql = "     SELECT d.departamentoid,
                            d.nome AS departamentonome,
                            l.localid,
                            l.nome AS localnome
                       FROM departamento d
                 INNER JOIN local l
                         ON l.localid = d.localid
                   ORDER BY l.nome, d.nome";

        $result = Transaction::runExecute($sql);

        $arrayDepartamento = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $departamento = new departamentoModel();
            $departamento->setDepartamentoid($row['departamentoid']);
            $departamento->setLocalid($row['localid']);
            $departamento->setDepartamentonome($row['departamentonome']);
            $departamento->setLocalnome($row['localnome']);
            $departamento->setLocalid($row['localid']);
            
            array_push($arrayDepartamento, $departamento);

        }

        return $arrayDepartamento;

    }
    
    public static function listdepartamentoToLocal($localid) {

        $sql = "    SELECT d.departamentoid, 
                           d.localid, 
                           d.nome AS departamentonome,
                           l.nome AS localnome
                      FROM departamento d
                INNER JOIN local l
                        ON l.localid = d.localid
                     WHERE d.localid = '{$localid}'";

        $result = Transaction::runExecute($sql);

        $arrayDepartamento = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $departamento = new departamentoModel();
            $departamento->setDepartamentoid($row['departamentoid']);
            $departamento->setDepartamentonome($row['departamentonome']);

            array_push($arrayDepartamento, $departamento);
        }

        return $arrayDepartamento;
    }

    public static function getDepartamento($departamento) {

        $sql = "     SELECT d.departamentoid,
                            d.nome AS departamentonome,
                            l.localid,
                            l.nome AS localnome
                       FROM departamento d
                 INNER JOIN local l
                         ON l.localid = d.localid
                      WHERE d.departamentoid='{$departamento->getDepartamentoid()}'
                   ORDER BY l.nome, d.nome";

        $result = Transaction::runExecute($sql);

        $departamento = new departamentoModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $departamento = new departamentoModel();
            $departamento->setDepartamentoid($row['departamentoid']);
            $departamento->setDepartamentonome($row['departamentonome']);
            $departamento->setLocalid($row['localid']);
            $departamento->setLocalnome($row['localnome']);

        }

        return $departamento;

    }
    
    public static function isExists($departamento) {

        $sql = "SELECT * 
                  FROM departamento
                 WHERE nome ILIKE '{$departamento->getDepartamentonome()}'
                   AND localid = '{$departamento->getLocalid()}'";

        $result = Transaction::runExecute($sql);

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            return true;
        }
        return false;
    }

}

?>