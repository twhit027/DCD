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
    private $db = NULL;
    private $connection_string = NULL;
    private $db_host = DB_HOST;
    private $db_user = DB_USER;
    private $db_pass = DB_PASS;
    private $db_name = DB_NAME;
    private $con = false;
    private $result = array();

    public function __construct()
    {
        try {
            $this->connection_string = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8';
            parent::__construct($this->connection_string, $this->db_user, $this->db_pass);
            $this->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->con = true;
        } catch (\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /// Get an associative array of results for the sql.
    public function getAssoc($sql, $params = array())
    {
        try {
            $stmt = $this->prepare($sql);
            $params = is_array($params) ? $params : array($params);
            $stmt->execute($params);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo 'Error: ' . $e->getMessage();

            /*
             throw new Exception(
                __METHOD__ . 'Exception Raised for sql: ' . var_export($sql, true) .
                ' Params: ' . var_export($params, true) .
                ' Error_Info: ' . var_export($this->errorInfo(), true),
                0,
                $e);
            */
        }
    }

    public function getCount($sql) {
        $stmt = $this->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}