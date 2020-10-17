<?php

namespace _core;

class DB
{

    private $link;
    private $where;
    private $cols;
    private $table;
    private $type;
    private $values;
    private $join;

    public function __construct()
    {
        $this->err = new ErrorHandler;
    }

    private function linkStart()
    {
        $this->link = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->link->connect_errno) {
            $msg = "Failed to connect to MySQL: " . $this->link->connect_error;
            trigger_error($msg, E_USER_ERROR);
        }
    }

    private function processQueryCols($set = false, $values = false)
    {
        $query = '';
        $queryValues = '';
        for ($i = 0; $i < count($this->cols); $i++) {
            $query .= ($i == 0 ? ' ' : ', ') . ($set ?
                (array_keys($this->cols)[$i] . " = $this->cols[$i]") : ($values ? array_keys($this->cols)[$i] : $this->cols[$i]));

            if ($values) {
                $val = mysqli_real_escape_string($this->link, $this->cols[$i]);
                $queryValues .= ($i == 0 ? ' ' : ' ,') . "'$val'";
            }
        }
        $this->cols = $query;
        $this->values = $queryValues;
    }

    private function queryBuilder()
    {
        $query = $this->type;
        $this->linkStart();
        $this->cols && $this->processQueryCols($query == 'UPDATE', $query == 'INSERT');
        switch ($this->type) {
            case 'INSERT':
                $query .= " INTO $this->table ($this->cols) VALUES ($this->values) ";
                break;
            case 'SELECT':
                $cols = $this->cols ? $this->cols : '*';
                $query .= " $cols FROM $this->table";
                break;
            case 'UPDATE':
                $query .= " $this->table SET $this->cols";
                break;
            case 'DELETE':
                $query .= " FROM $this->table";
                break;

            default:
                $msg = "Unexpected operation, use queryBuilder only for SELECT, INSERT, UPDATE and DELETE." .
                    "<BR>For other operations, execute the query directly with \$this->db->exec.";
                trigger_error($msg, E_USER_ERROR);
                break;
        }
        if ($this->join) {
            foreach ($this->join as $join) {
                $query .= $join;
            }
        }
        if ($this->where) {
            $query .= " WHERE";
            for ($i = 0; $i < count($this->where); $i++) {
                $wType = $this->where[$i]['type'];
                $wData = $this->where[$i]['data'];

                $key = array_keys($wData)[0];
                $val = mysqli_real_escape_string($this->link, $wData[$key]);

                @$subW = explode(' ', $key);

                $query .= $i > 0 ? " $wType" : "";
                if (!is_numeric($key)) {
                    $query .= (count($subW) > 1) ? " ($key '$val')" : " ($key = '$val')";
                } else {
                    $query .= " ($val)";
                }
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

    public function exec($query)
    {
        if (!$rows = $this->link->query($query, MYSQLI_ASSOC)) {
            $msg = "Mysql error: " . $this->link->error;
            trigger_error($msg, E_USER_ERROR);
        }
        while ($row = $rows->fetch_assoc()) {
            $res[] = $row;
        };
        $this->link->close();
        return $res;
    }

    public function get($run = true)
    {
        $query = $this->queryBuilder();
        $this->clearQuery();
        return $run ? $this->exec($query) : $query;
    }

    public function insert($table, $cols)
    {
        $this->type = "INSERT";
        $this->table = $table;
        if (is_array($cols)) {
            $this->cols = $cols;
        } else {
            $this->cols = [$cols];
        }

        return $this;
    }

    public function select($table, $cols = '')
    {
        $this->type = "SELECT";
        $this->table = $table;
        if (is_array($cols)) {
            $this->cols = $cols;
        } else {
            if ($cols) $this->cols = [$cols];
        }

        return $this;
    }

    public function update($table, $cols)
    {

        $this->type = "UPDATE";
        $this->table = $table;
        if (is_array($cols)) {
            $this->cols = $cols;
        } else {
            $this->cols = [$cols];
        }

        return $this;
    }

    public function delete($table)
    {
        $this->type = "DELETE";
        $this->table = $table;
        return $this;
    }

    public function where($columOrArray, $value = '')
    {
        if (is_array($columOrArray)) {
            $this->where = array_merge($this->where, $columOrArray);
        } else {
            if (empty($columOrArray)) {
                $msg = '$this->db->where: first parameter is empty';
                trigger_error($msg, E_USER_ERROR);
            }
            $xCol = explode(' ', $columOrArray);
            if (count($xCol) > 2 && !empty($value)) {
                $err = '$this->db->where: first parameter expects a maximum of 2 arguments in the same string when second parmeter is especified.' .
                    '<BR>Ex: "colum !=" ';
                trigger_error($err, E_USER_ERROR);
            }
            if ($value != '') {
                $this->where[] = [
                    'data' => [$columOrArray => "$value"],
                    'type' => 'AND'
                ];
            } else {
                $this->where[] = [
                    'data' => [$columOrArray],
                    'type' => 'AND'
                ];
            }
        }
        return $this;
    }

    public function orWhere($columOrArray, $value = '')
    {
        if (is_array($columOrArray)) {
            $this->where = array_merge($this->where, $columOrArray);
        } else {
            if (empty($columOrArray)) {
                $msg = '$this->db->or_where: first parameter is empty';
                trigger_error($msg, E_USER_ERROR);
            }
            $xCol = explode(' ', $columOrArray);
            if (count($xCol) > 2 && !empty($value)) {
                $err = '$this->db->or_where: first parameter expects a maximum of 2 arguments in the same string when second parmeter is especified.' .
                    '<BR>Ex: "colum !=" ';
                trigger_error($err, E_USER_ERROR);
            }
            if ($value) {
                $this->where[] = [
                    'data' => [$columOrArray => $value],
                    'type' => 'OR'
                ];
            } else {
                $this->where[] = [
                    'data' => [$columOrArray],
                    'type' => 'OR'
                ];
            }
        }
        return $this;
    }

    public function join($table, $on, $joinType = '')
    {
        if ($joinType) $joinType .= ' ';
        $this->join[] = " $joinType" . "JOIN $table ON ($on)";
        return $this;
    }
}
