<?php

require_once $_SERVER['DOCUMENT_ROOT'] . $urlroot . '/model/temaModel.php';

class temaAction extends temaModel {

    public static function listTema() {

        $sql = "     SELECT temaid,
                            nome,
                            link,
                            img
                       FROM tema
                   ORDER BY temaid";

        $result = Transaction::runExecute($sql);

        $arrayTema = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $tema = new temaModel();
            $tema->setTemaid($row['temaid']);
            $tema->setNome($row['nome']);
            $tema->setLink($row['link']);
            $tema->setImg($row['img']);
            array_push($arrayTema, $tema);
        }

        return $arrayTema;
    }

    public static function getTema($tema) {

        $sql = "     SELECT temaid,
                            nome,
                            link
                       FROM tema
                      WHERE temaid='{$tema->getTemaid()}'";

        $result = Transaction::runExecute($sql);

        $tema = new temaModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $tema = new temaModel();
            $tema->setTemaid($row['temaid']);
            $tema->setNome($row['nome']);
            $tema->setLink($row['link']);
            $tema->setImg($row['img']);
        }

        return $tema;
    }

}
