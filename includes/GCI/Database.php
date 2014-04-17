<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 3/15/14
 * Time: 4:13 PM
 */

namespace GCI;

class Database extends \PDO
{
    private $connection_string = NULL;
    private $con = false;
    private $log;

    public function __construct()
    {
        try {
            $this->connection_string = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8';
            parent::__construct($this->connection_string, DB_USER, DB_PASS);
            $this->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->con = true;
            if (empty($logDir)) {
                $logDir = LOGGING_DIR;
            }
            $this->setLog($logDir);
        } catch (\PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") attempting to connect to database";
            $this->log->logError($logText);
        }
    }

    public function setLog($logDir = LOGGING_DIR, $logLevel = LOGGING_LEVEL)
    {
        $this->log = \KLogger::instance($logDir, $logLevel);
    }

    /// Get an associative array of results for the sql.
    public function getAssoc($sql, $params = array())
    {
        $ret = false;
        try {
            $time_start = microtime(true);
            $stmt = $this->prepare($sql);
            $params = is_array($params) ? $params : array($params);
            $stmt->execute($params);

            $this->log->logInfo('sql: ('.$sql,') took ('.(microtime(true) - $time_start).')');

            $ret = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") problem with query ($sql)";
            $this->log->logError($logText);

            /*
             throw new Exception(
                __METHOD__ . 'Exception Raised for sql: ' . var_export($sql, true) .
                ' Params: ' . var_export($params, true) .
                ' Error_Info: ' . var_export($this->errorInfo(), true),
                0,
                $e);
            */
        }

        return $ret;
    }

    public function getCount($sql) {
        $ret = false;
        try {
            $time_start = microtime(true);
            $stmt = $this->prepare($sql);
            $stmt->execute();
            $this->log->logInfo('sql: ('.$sql.') took ('.(microtime(true) - $time_start).')');
            $ret = $stmt->fetchColumn();
        } catch (\PDOException $e) {
            $logText = "Message:(" . $e->getMessage() . ") problem with query ($sql)";
            $this->log->logError($logText);
        }

        return $ret;
    }
}