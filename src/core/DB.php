<?php

namespace core;

class DB
{

    private $where = [];
    private $cols = [];
    private $table;
    private $type = '';

    public function __construct()
    {
        /* echo "<pre>";
        print_r(debug_backtrace());
        //die(); */
    }

    private function queryBuilder()
    {
        $query = $this->type;
        if (!$this->cols) {
            $query .= " *";
        } else {
            for ($i = 0; $i < count($this->cols); $i++) {
                $query .= ($i == 0 ? ' ' : ', ') . $this->cols[$i];
            }
        }
        $query .= " FROM $this->table";
        if ($this->where) {
            $query .= " WHERE";
            for ($i = 0; $i < count($this->where); $i++) {
                if ($i > 5) {
                    die($i . " - $query");
                }
                $key = array_keys($this->where)[$i];
                $val = $this->where[$key];
                @$subW = explode(' ', array_keys($this->where)[$i]);
                $query .= $i > 0 ? " AND" : "";
                $query .= (count($subW) > 1) ? " ($key '$val')" : " ($key = '$val')";
            }
        }

        return $query;
    }

    public function clearQuery()
    {
        unset($this->where);
        unset($this->cols);
        unset($this->table);
        unset($this->type);
    }

    public function getQuery()
    {
        $query = $this->queryBuilder();
        $this->clearQuery();
        return $query;
    }

    public function select($table, $cols = [])
    {
        $this->type = "SELECT";
        $this->table = $table;
        if ($cols) {
            $this->cols = $cols;
        }

        return $this;
    }

    public function where($columOrArray, $value = '')
    {
        if (is_array($columOrArray)) {
            $this->where = array_merge($this->where, $columOrArray);
        } else {
            $xCol = explode(' ', $columOrArray);
            if (count($xCol) > 2 || empty($columOrArray)) {
                $debug = debug_backtrace();
                $callerFunc = $debug[1]['function'];
                @$callerLine = $debug[1]['line'];
                @$callerFile = $debug[1]['file'];
                @$callerClass = $debug[1]['class'];
                $errStr = '$this-> db-> get_where: the first parameter expects a maximum of 2 arguments in the same string.' .
                    '<BR>Ex: "colum !=" ' .

                    ($callerFunc ? "<BR>Caller Function: $callerFunc" : '') .
                    ($callerLine ? "<BR>Caller Line: $callerLine" : '') .
                    ($callerFile ? "<BR>Caller File: $callerFile" : '') .
                    ($callerClass ? "<BR>Caller Class: $callerClass" : '') .
                    '<BR>';
                trigger_error($errStr, E_USER_ERROR);
            }
            $this->where[$columOrArray] = $value;
        }
        return $this;
    }
}
