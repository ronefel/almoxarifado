<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/fornecedorModel.php';

class fornecedorAction extends fornecedorModel {

    public static function insertFornecedor($fornecedor) {

        $sql = "insert into fornecedor (
                            fornecedorgrupoid,
                            razao,
                            fantazia,
                            endereco,
                            numero,
                            bairro,
                            cidadeid,
                            cnpj_cpf,
                            inscricao_rg,
                            telefone,
                            contato,
                            datacadastro,
                            observacao,
                            ativo,
                            email)
                   values ('{$fornecedor->getFornecedorgrupoid()}',
                           '{$fornecedor->getRazao()}',
                           '{$fornecedor->getFantazia()}',
                           '{$fornecedor->getEndereco()}',
                           '{$fornecedor->getNumero()}',
                           '{$fornecedor->getBairro()}',
                           '{$fornecedor->getCidadeid()}',
                           '{$fornecedor->getCnpj_cpf()}',
                           '{$fornecedor->getInscricao_rg()}',
                           '{$fornecedor->getTelefone()}',
                           '{$fornecedor->getContato()}',
                           '{$fornecedor->getDatacadastro()}',
                           '{$fornecedor->getObservacao()}',
                           '{$fornecedor->getAtivo()}',
                           '{$fornecedor->getEmail()}')";

        return Transaction::runExecute($sql);

    }

    public static function updateFornecedor($fornecedor) {

        $sql = "update fornecedor
                   set fornecedorgrupoid='{$fornecedor->getFornecedorgrupoid()}',
                       razao='{$fornecedor->getRazao()}',
                       fantazia='{$fornecedor->getFantazia()}',
                       endereco='{$fornecedor->getEndereco()}',
                       numero='{$fornecedor->getNumero()}',
                       bairro='{$fornecedor->getBairro()}',
                       cidadeid='{$fornecedor->getCidadeid()}',
                       cnpj_cpf='{$fornecedor->getCnpj_cpf()}',
                       inscricao_rg='{$fornecedor->getInscricao_rg()}',
                       telefone='{$fornecedor->getTelefone()}',
                       contato='{$fornecedor->getContato()}',
                       datacadastro='{$fornecedor->getDatacadastro()}',
                       observacao='{$fornecedor->getObservacao()}',
                       ativo='{$fornecedor->getAtivo()}',
                       email='{$fornecedor->getEmail()}'
                 where fornecedorid='{$fornecedor->getFornecedorid()}'";

        return Transaction::runExecute($sql);

    }

    public static function deleteFornecedor($fornecedor) {

        $sql = "DELETE FROM fornecedor WHERE fornecedorid='{$fornecedor->getFornecedorid()}'";

        $result = Transaction::runExecute($sql);
        if ($result) {
            echo 'sucesso';
        } else {

            $total = "";
            $sql1 = "    SELECT count(F.fornecedorid) as total
                           FROM fornecedor F
                     INNER JOIN compra C
                             ON C.fornecedorid = F.fornecedorid
                          WHERE F.fornecedorid = '{$fornecedor->getFornecedorid()}'";

            $result1 = Transaction::runExecute($sql1);

            if ($result1) {
                while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
                    $total = $row['total'];
                }

                echo 'Não é possível excluir esse fornecedor, '
                . 'esse fornecedor está vinculado a '
                . $total . ' compra(s).';
            } else {
                echo 'Ouve um erro na hora de excluir o fornecedor.';
            }
        }

    }

    public static function listFornecedor() {

        $sql = "     SELECT F.fornecedorid,
                            F.fornecedorgrupoid,
                            FG.nome AS fornecedorgruponome,
                            F.razao,
                            F.fantazia,
                            F.endereco,
                            F.numero,
                            F.bairro,
                            F.cidadeid,
                            F.cnpj_cpf,
                            F.inscricao_rg,
                            F.telefone,
                            F.contato,
                            F.datacadastro,
                            F.observacao,
                            F.ativo,
                            F.email
                       FROM fornecedor F
                 INNER JOIN fornecedorgrupo FG
                         ON FG.fornecedorgrupoid = F.fornecedorgrupoid";

        $result = Transaction::runExecute($sql);

        $arrayFornecedor = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $fornecedor = new fornecedorModel();
            $fornecedor->setFornecedorid($row['fornecedorid']);
            $fornecedor->setFornecedorgrupoid($row['fornecedorgrupoid']);
            $fornecedor->setFornecedorgruponome($row['fornecedorgruponome']);
            $fornecedor->setRazao($row['razao']);
            $fornecedor->setFantazia($row['fantazia']);
            $fornecedor->setEndereco($row['endereco']);
            $fornecedor->setNumero($row['numero']);
            $fornecedor->setBairro($row['bairro']);
            $fornecedor->setCidadeid($row['cidadeid']);
            $fornecedor->setCnpj_cpf($row['cnpj_cpf']);
            $fornecedor->setInscricao_rg($row['inscricao_rg']);
            $fornecedor->setTelefone($row['telefone']);
            $fornecedor->setContato($row['contato']);
            $fornecedor->setDatacadastro($row['datacadastro']);
            $fornecedor->setObservacao($row['observacao']);
            $fornecedor->setAtivo($row['ativo']);
            $fornecedor->setEmail($row['email']);
            array_push($arrayFornecedor, $fornecedor);

        }

        return $arrayFornecedor;

    }

    public static function getFornecedor($fornecedor) {

        $sql = "     SELECT fornecedorid,
                            fornecedorgrupoid,
                            razao,
                            fantazia,
                            endereco,
                            numero,
                            bairro,
                            cidadeid,
                            cnpj_cpf,
                            inscricao_rg,
                            telefone,
                            contato,
                            datacadastro,
                            observacao,
                            ativo,
                            email
                       FROM fornecedor
                      WHERE fornecedorid='{$fornecedor->getFornecedorid()}'";

        $result = Transaction::runExecute($sql);

        $fornecedor = new fornecedorModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $fornecedor = new fornecedorModel();
            $fornecedor->setFornecedorid($row['fornecedorid']);
            $fornecedor->setFornecedorgrupoid($row['fornecedorgrupoid']);
            $fornecedor->setRazao($row['razao']);
            $fornecedor->setFantazia($row['fantazia']);
            $fornecedor->setEndereco($row['endereco']);
            $fornecedor->setNumero($row['numero']);
            $fornecedor->setBairro($row['bairro']);
            $fornecedor->setCidadeid($row['cidadeid']);
            $fornecedor->setCnpj_cpf($row['cnpj_cpf']);
            $fornecedor->setInscricao_rg($row['inscricao_rg']);
            $fornecedor->setTelefone($row['telefone']);
            $fornecedor->setContato($row['contato']);
            $fornecedor->setDatacadastro($row['datacadastro']);
            $fornecedor->setObservacao($row['observacao']);
            $fornecedor->setAtivo($row['ativo']);
            $fornecedor->setEmail($row['email']);

        }

        return $fornecedor;

    }

}

?>