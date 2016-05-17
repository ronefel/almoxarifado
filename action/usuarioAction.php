<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/usuarioModel.php';

class usuarioAction extends usuarioModel {

    public static function insertUsuario($usuario) {

        $sql = "insert into usuario (
                            nome,
                            login,
                            senha,
                            ativo,
                            email,
                            tipousuario,
                            departamentoid)
                   values ('{$usuario->getUsuarionome()}',
                           '{$usuario->getLogin()}',
                           '{$usuario->getSenha()}',
                           '{$usuario->getAtivo()}',
                           '{$usuario->getEmail()}',
                           '{$usuario->getTipousuario()}',
                           '{$usuario->getDepartamentoid()}')";

        return Transaction::runExecute($sql);

    }

    public static function updateUsuario($usuario) {
        
        $args = array();

        $sql = 'update usuario set ';

        if (isset($usuario->nome)) {
            $sql .= 'nome = ?, ';
            $args[] = $usuario->getUsuarionome();
        }
        if (isset($usuario->login)) {
            $sql .= 'login = ?, ';
            $args[] = $usuario->getLogin();
        }
        if (isset($usuario->senha)) {
            $sql .= 'senha = ?, ';
            $args[] = $usuario->getSenha();
        }
        if (isset($usuario->ativo)) {
            $sql .= 'ativo = ?, ';
            $args[] = $usuario->getAtivo();
        }
        if (isset($usuario->email)) {
            $sql .= 'email = ?, ';
            $args[] = $usuario->getEmail();
        }
        if (isset($usuario->tipousuario)) {
            $sql .= 'tipousuario = ?, ';
            $args[] = $usuario->getTipousuario();
        }
        if ($usuario->getdepartamentoid() != null) {
            $sql .= 'departamentoid = ?, ';
            $args[] = $usuario->getDepartamentoid();
        }
        if ($usuario->getTemaid() != null) {
            $sql .= 'temaid = ?, ';
            $args[] = $usuario->getTemaid();
        }

        $args[] = $usuario->getUsuarioid();

        for ($i = 0; $i < count($args); $i++) {
            if (strlen($args[$i]) == 0) {
                $args[$i] = null; // Aqui faz o elemento vazio ficar NULL
            }
        }

        $sql = substr($sql, 0, strlen($sql) - 2) . ' where usuarioid = ?';

        return Transaction::runPrepare($sql, $args);
        
//        
//
//        $sql = "update usuario
//                   set nome='{$usuario->getUsuarionome()}',
//                       login='{$usuario->getLogin()}',
//                       senha='{$usuario->getSenha()}',
//                       ativo='{$usuario->getAtivo()}',
//                       email='{$usuario->getEmail()}',
//                       tipousuario='{$usuario->getTipousuario()}',
//                       departamentoid='{$usuario->getDepartamentoid()}'
//                 where usuarioid='{$usuario->getUsuarioid()}'";
//
//        return Transaction::runExecute($sql);

    }

    public static function deleteUsuario($usuario) {

        $sql = "DELETE FROM usuario WHERE usuarioid='{$usuario->getUsuarioid()}'";

        $result = Transaction::runExecute($sql);
        if ($result) {
            echo 'sucesso';
        } else {

            $total = "";
            $sql1 = "    SELECT count(U.usuarioid) as total
                          FROM usuario U
                    INNER JOIN requisicao R
                            ON R.usuarioid = U.usuarioid
                         WHERE U.usuarioid = '{$usuario->getUsuarioid()}'";

            $result1 = Transaction::runExecute($sql1);

            if ($result1) {
                while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
                    $total = $row['total'];
                }

                echo 'Não é possível excluir esse usuário, esse usuário está vinculado a ' . $total . ' requisição(ões).';
            }else{
                echo 'Ouve um erro na hora de excluir o usuário.';
            }
        }

    }

    public static function listUsuario() {

        $sql = "     SELECT U.usuarioid,
                            U.nome AS usuarionome,
                            U.login,
                            U.senha,
                            U.ativo,
                            U.email,
                            U.tipousuario,
                            U.departamentoid,
                            D.nome AS departamentonome,
                            L.localid,
                            L.nome AS localnome
                       FROM usuario U
                 INNER JOIN departamento D
                         ON D.departamentoid = U.departamentoid
                 INNER JOIN local L
                         ON L.localid = D.localid";

        $result = Transaction::runExecute($sql);

        $arrayUsuario = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $usuario = new usuarioModel();
            $usuario->setUsuarioid($row['usuarioid']);
            $usuario->setUsuarionome($row['usuarionome']);
            $usuario->setLogin($row['login']);
            $usuario->setSenha($row['senha']);
            $usuario->setAtivo($row['ativo']);
            $usuario->setEmail($row['email']);
            $usuario->setTipousuario($row['tipousuario']);
            $usuario->setDepartamentoid($row['departamentoid']);
            $usuario->setDepartamentoNome($row['departamentonome']);
            $usuario->setLocalid($row['localid']);
            $usuario->setLocalnome($row['localnome']);
            
            array_push($arrayUsuario, $usuario);

        }

        return $arrayUsuario;

    }

    public static function listRequisitante() {

        $sql = "     SELECT U.usuarioid,
                            U.nome AS usuarionome,
                            U.login,
                            U.senha,
                            U.ativo,
                            U.email,
                            U.tipousuario,
                            U.departamentoid,
                            D.nome AS departamentonome,
                            L.localid,
                            L.nome AS localnome
                       FROM usuario U
                 INNER JOIN departamento D
                         ON D.departamentoid = U.departamentoid
                 INNER JOIN local L
                         ON L.localid = D.localid
                      WHERE U.tipousuario = 2";

        $result = Transaction::runExecute($sql);

        $arrayUsuario = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $usuario = new usuarioModel();
            $usuario->setUsuarioid($row['usuarioid']);
            $usuario->setUsuarionome($row['usuarionome']);
            $usuario->setLogin($row['login']);
            $usuario->setSenha($row['senha']);
            $usuario->setAtivo($row['ativo']);
            $usuario->setEmail($row['email']);
            $usuario->setTipousuario($row['tipousuario']);
            $usuario->setDepartamentoid($row['departamentoid']);
            $usuario->setDepartamentoNome($row['departamentonome']);
            $usuario->setLocalid($row['localid']);
            $usuario->setLocalnome($row['localnome']);
            
            array_push($arrayUsuario, $usuario);

        }

        return $arrayUsuario;

    }

    public static function getUsuario($usuario) {

        $sql = "     SELECT U.usuarioid,
                            U.nome AS usuarionome,
                            U.login,
                            U.senha,
                            U.ativo,
                            U.email,
                            U.tipousuario,
                            U.departamentoid,
                            D.nome AS departamentonome,
                            L.localid,
                            L.nome AS localnome,
                            U.temaid,
                            T.link AS link
                       FROM usuario U
                 INNER JOIN departamento D
                         ON D.departamentoid = U.departamentoid
                 INNER JOIN local L
                         ON L.localid = D.localid
                 INNER JOIN tema T
                         ON T.temaid = U.temaid
                      WHERE U.usuarioid='{$usuario->getUsuarioid()}'";

        $result = Transaction::runExecute($sql);

        $usuario = new usuarioModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $usuario = new usuarioModel();
            $usuario->setUsuarioid($row['usuarioid']);
            $usuario->setUsuarionome($row['usuarionome']);
            $usuario->setLogin($row['login']);
            $usuario->setSenha($row['senha']);
            $usuario->setAtivo($row['ativo']);
            $usuario->setEmail($row['email']);
            $usuario->setTipousuario($row['tipousuario']);
            $usuario->setDepartamentoid($row['departamentoid']);
            $usuario->setDepartamentonome($row['departamentonome']);
            $usuario->setLocalid($row['localid']);
            $usuario->setLocalnome($row['localnome']);
            $usuario->setTemaid($row['temaid']);
            $usuario->setLink($row['link']);

        }

        return $usuario;

    }
    
    public static function loginUsuario($usuario) {

        $sql = "     SELECT U.usuarioid,
                            U.nome AS usuarionome,
                            U.login,
                            U.senha,
                            U.ativo,
                            U.email,
                            U.tipousuario,
                            U.departamentoid,
                            D.nome AS departamentonome,
                            L.localid,
                            L.nome AS localnome,
                            U.temaid,
                            T.link AS link
                       FROM usuario U
                 INNER JOIN departamento D
                         ON D.departamentoid = U.departamentoid
                 INNER JOIN local L
                         ON L.localid = D.localid
                 INNER JOIN tema T
                         ON T.temaid = U.temaid
                      WHERE U.login='{$usuario->getLogin()}'
                        AND U.senha='{$usuario->getSenha()}'";

        $result = Transaction::runExecute($sql);

        $usuario = new usuarioModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $usuario = new usuarioModel();
            $usuario->setUsuarioid($row['usuarioid']);
            $usuario->setUsuarionome($row['usuarionome']);
            $usuario->setLogin($row['login']);
            $usuario->setSenha($row['senha']);
            $usuario->setAtivo($row['ativo']);
            $usuario->setEmail($row['email']);
            $usuario->setTipousuario($row['tipousuario']);
            $usuario->setDepartamentoid($row['departamentoid']);
            $usuario->setDepartamentonome($row['departamentonome']);
            $usuario->setLocalid($row['localid']);
            $usuario->setLocalnome($row['localnome']);
            $usuario->setTemaid($row['temaid']);
            $usuario->setLink($row['link']);

        }

        return $usuario;

    }

}

?>