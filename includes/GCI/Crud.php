<?php
/**
 * Created by DCDGroup.
 * User: JHICKS
 * Date: 7/2/14
 * Time: 10:09 AM
 */

namespace GCI;

class Crud
{
    private $db;

    private $variables;

    private $log;

    public function __construct($data = array(), $table = '', $pk = '')
    {
        $this->db = new Database();
        $this->variables = $data;

        if (empty($this->table) || empty($this->pk)) {
            throw new Exception('need table and pk');
        }

        if (empty($logDir)) {
            $logDir = LOGGING_DIR;
        }
        $this->setLog($logDir);
    }

    public function __set($name, $value)
    {
        if (strtolower($name) === $this->pk) {
            $this->variables[$this->pk] = $value;
        } else {
            $this->variables[$name] = $value;
        }
    }

    public function __get($name)
    {
        if (is_array($this->variables)) {
            if (array_key_exists($name, $this->variables)) {
                return $this->variables[$name];
            }
        }

        $trace = debug_backtrace();
        $logText = "Message:( Undefined property via __get(): $name ) in file " . $trace[0]['file'] . "on line " . $trace[0]['line'];
        $this->log->logError($logText);

        return null;
    }

    public function __isset($name)
    {
        return isset($this->variables[$name]);
    }

    public function __unset($name)
    {
        unset($this->variables[$name]);
    }

    public function setLog($logDir = LOGGING_DIR, $logLevel = LOGGING_LEVEL)
    {
        $this->log = \KLogger::instance($logDir, $logLevel);
    }

    public function create()
    {
        $bindings = $this->variables;

        if (!empty($bindings)) {
            $fields = array_keys($bindings);
            $fieldsvals = array(implode(",", $fields), ":" . implode(",:", $fields));
            $sql = "INSERT INTO " . $this->table . " (" . $fieldsvals[0] . ") VALUES (" . $fieldsvals[1] . ")";
        } else {
            $sql = "INSERT INTO " . $this->table . " () VALUES ()";
        }

        return $this->db->getAssoc($sql, $bindings);
    }

    public function read($id = "")
    {
        $id = (empty($this->variables[$this->pk])) ? $id : $this->variables[$this->pk];

        if (!empty($id)) {
            $sql = "SELECT * FROM " . $this->table . " WHERE " . $this->pk . "= :" . $this->pk . " LIMIT 1";
            $this->variables = $this->db->getAssoc($sql, array($this->pk => $id));
        }
    }

    public function update($id = "0")
    {
        $this->variables[$this->pk] = (empty($this->variables[$this->pk])) ? $id : $this->variables[$this->pk];

        $fieldsvals = '';
        $columns = array_keys($this->variables);

        foreach ($columns as $column) {
            if ($column !== $this->pk)
                $fieldsvals .= $column . " = :" . $column . ",";
        }

        $fieldsvals = substr_replace($fieldsvals, '', -1);

        if (count($columns) > 1) {
            $sql = "UPDATE " . $this->table . " SET " . $fieldsvals . " WHERE " . $this->pk . "= :" . $this->pk;
            return $this->db->getAssoc($sql, $this->variables);
        }
    }

    public function delete($id = "")
    {
        $id = (empty($this->variables[$this->pk])) ? $id : $this->variables[$this->pk];

        if (!empty($id)) {
            $sql = "DELETE FROM " . $this->table . " WHERE " . $this->pk . "= :" . $this->pk . " LIMIT 1";
            return $this->db->getAssoc($sql, array($this->pk => $id));
        }
    }

    public function all(){
        return $this->db->getAssoc("SELECT * FROM " . $this->table);
    }
} 