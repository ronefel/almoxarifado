<?php 

require_once './logModel.php';

class logAction extends logModel {

    public static function insertLog($log) {

        $sql = "insert into log (
                            usuarioid,
                            descricao,
                            data)
                   values ('{$log->getUsuarioid()}',
                           '{$log->getDescricao()}',
                           '{$log->getData()}')";

        return siteTransaction::runExecute($sql);

    }

    public static function updateLog($log) {

        $sql = "update log
                   set usuarioid='{$log->getUsuarioid()}',
                       descricao='{$log->getDescricao()}',
                       data='{$log->getData()}'
                 where logid='{$log->getLogid()}'";

        return siteTransaction::runExecute($sql);

    }

    public static function deleteLog($log) {

        $sql = "DELETE FROM log WHERE logid='{$log->getLogid()}'";

        return siteTransaction::runExecute($sql);

    }

    public static function listLog() {

        $sql = "     SELECT logid,
                            usuarioid,
                            descricao,
                            data
                       FROM log";

        $result = siteTransaction::runExecute($sql);

        $arrayLog = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $log = new logModel();
            $log->setLogid($row['logid']);
            $log->setUsuarioid($row['usuarioid']);
            $log->setDescricao($row['descricao']);
            $log->setData($row['data']);
            array_push($arrayLog, $log);

        }

        return $arrayLog;

    }

    public static function getLog($log) {

        $sql = "     SELECT logid,
                            usuarioid,
                            descricao,
                            data
                       FROM log
                      WHERE logid='{$log->getLogid()}'";

        $result = siteTransaction::runExecute($sql);

        $log = new logModel();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $log = new logModel();
            $log->setLogid($row['logid']);
            $log->setUsuarioid($row['usuarioid']);
            $log->setDescricao($row['descricao']);
            $log->setData($row['data']);

        }

        return $log;

    }

}

?>