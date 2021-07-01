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
    private $groupBy;
    private $orderBy;
    private $limit;

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
        }else{
            $this->link->set_charset(DB_CHARSET);
        }
    }

    private function processQueryCols($set = false, $values = false)
    {
        $query = '';
        $queryValues = '';
        for ($i = 0; $i < count($this->cols); $i++) {
            $query .= ($i == 0 ? ' ' : ', ') . ($set ?
                (array_keys($this->cols)[$i] . " = '" . mysqli_real_escape_string(
                    $this->link,
                    $this->cols[array_keys($this->cols)[$i]]
                ) . "'") : ($values ? array_keys($this->cols)[$i] : $this->cols[$i]));

            if ($values) {
                $val = mysqli_real_escape_string($this->link, $this->cols[array_keys($this->cols)[$i]]);
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

                $kv = '';

                foreach ($wData as $key => $val) {
                    @$subW = explode(' ', $key);
                    $val = mysqli_real_escape_string($this->link, $val);
                    $kv .= $kv ? ' AND ' : '';
                    $kv .= (count($subW) > 1) ? " $key '$val'" : " $key = '$val'";
                }
                if (count($wData) > 1) $val = $kv;

                $query .= $i > 0 ? " $wType" : "";
                if (!is_numeric($key) && count($wData) == 1) {
                    $query .= (count($subW) > 1) ? " ($key '$val')" : " ($key = '$val')";
                } else {
                    $query .= " ($val)";
                }
            }
        }

        if ($this->groupBy) $query .= " GROUP BY {$this->groupBy}";
        if ($this->orderBy) $query .= " ORDER BY {$this->orderBy}";
        if ($this->limit) $query .= " LIMIT {$this->limit}";


        return $query;
    }

    private function runQuery($query)
    {
        if (!$rows = $this->link->query($query, MYSQLI_ASSOC)) {
            $msg = "Mysql error: " . $this->link->error;
            trigger_error($msg, E_USER_ERROR);
        }
        if (!is_bool($rows)) {
            while ($row = $rows->fetch_assoc()) {
                $res[] = $row;
            };
        }

        $this->link->close();
        return $res;
    }

    /**
     * Clears all settings not performed in the query builder.
     */
    public function clearQuery()
    {
        unset($this->where);
        unset($this->cols);
        unset($this->table);
        unset($this->type);
        unset($this->values);
        unset($this->join);
        unset($this->limit);
        unset($this->orderBy);
        unset($this->groupBy);
    }

    /**
     * Executes a query on the database.
     * @param string $query The query string.
     * @return array
     */
    public function exec(string $query)
    {
        $this->linkStart();
        return $this->runQuery($query);
    }

    /**
     * Run the settings on query builder and return results.
     * @param bool $run Set false to return the query string.
     * @return array
     */
    public function get(bool $run = true)
    {
        $query = $this->queryBuilder();
        $this->clearQuery();
        return $run ? $this->runQuery($query) : $query;
    }

    /**
     * Run the settings on query builder and return only first result.
     * @return array
     */
    public function getRow()
    {
        $this->limit(1);
        $query = $this->queryBuilder();
        $this->clearQuery();
        return $this->runQuery($query)[0];
    }

    /**
     * Insert data on database table
     * @param string $table Table name
     * @param array $cols Array with columns names and values: ['foo' => 'bar']
     */
    public function insert(string $table, array $cols)
    {
        $this->type = "INSERT";
        $this->table = $table;
        $this->cols = $cols;

        return $this;
    }

    /**
     * Select data on database table
     * @param string $table Table name
     * @param string $cols Columns to select: 'col1, col2, col3'
     */
    public function select(string $table, string $cols = '')
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


    /**
     * Update data on database table
     * @param string $table Table name
     * @param array $cols Array with columns names and values: ['foo' => 'bar']
     */
    public function update(string $table, array $cols)
    {

        $this->type = "UPDATE";
        $this->table = $table;
        $this->cols = $cols;


        return $this;
    }

    /**
     * Delete data from table
     * @param string $table Table name
     */
    public function delete(string $table)
    {
        $this->type = "DELETE";
        $this->table = $table;
        return $this;
    }

    /**
     * Add condition to query
     * @param array/string $cols Array with columns names and values: ['foo' => 'bar', 'age >' => '17']
     * or String to only 1 condition (use second parameter to value): 'age >'
     * @param string $value Optional value to 1 condition: '17'
     */
    public function where($cols, string $value = '')
    {
        if (is_array($cols)) {
            $this->where[] = [
                'data' => $cols,
                'type' => 'AND'
            ];
        } else {
            if (empty($cols)) {
                $msg = '$this->db->where: first parameter is empty';
                trigger_error($msg, E_USER_ERROR);
            }
            $xCol = explode(' ', $cols);
            if (count($xCol) > 2 && !empty($value)) {
                $err = '$this->db->where: first parameter expects a maximum of 2 arguments in the same string when second parmeter is especified.' .
                    '<BR>Ex: "column !=" ';
                trigger_error($err, E_USER_ERROR);
            }
            if ($value != '') {
                $this->where[] = [
                    'data' => [$cols => "$value"],
                    'type' => 'AND'
                ];
            } else {
                $this->where[] = [
                    'data' => [$cols],
                    'type' => 'AND'
                ];
            }
        }
        return $this;
    }

    /**
     * Add 'OR' condition to query
     * @param array/string $cols Array with columns names and values: ['foo' => 'bar', 'age >' => '17']
     * or String to only 1 condition (use second parameter to value): 'age >'
     * @param string $value Optional value to 1 condition: '17'
     */
    public function orWhere($cols, string $value = '')
    {
        if (is_array($cols)) {
            $this->where[] = [
                'data' => $cols,
                'type' => 'OR'
            ];
        } else {
            if (empty($cols)) {
                $msg = '$this->db->or_where: first parameter is empty';
                trigger_error($msg, E_USER_ERROR);
            }
            $xCol = explode(' ', $cols);
            if (count($xCol) > 2 && !empty($value)) {
                $err = '$this->db->or_where: first parameter expects a maximum of 2 arguments in the same string when second parmeter is especified.' .
                    '<BR>Ex: "column !=" ';
                trigger_error($err, E_USER_ERROR);
            }
            if ($value) {
                $this->where[] = [
                    'data' => [$cols => $value],
                    'type' => 'OR'
                ];
            } else {
                $this->where[] = [
                    'data' => [$cols],
                    'type' => 'OR'
                ];
            }
        }
        return $this;
    }

    /**
     * Join tables data with condition
     * @param string $table Table name
     * @param string $on Condition
     * @param string $joinType Optional type to join (left, right, inner...)
     */
    public function join(string $table, string $on, string $joinType = '')
    {
        if ($joinType) $joinType .= ' ';
        $this->join[] = " $joinType" . "JOIN $table ON ($on)";
        return $this;
    }

    /**
     * Order results by column
     * @param string $col Column name
     */
    public function orderBy($col)
    {
        $this->orderBy = $col;
        return $this;
    }

    /**
     * Group results by column
     * @param string $col Column name
     */
    public function groupBy($col)
    {
        $this->groupBy = $col;
        return $this;
    }

    /**
     * Limit query results
     * @param int $n Rows limit
     */
    public function limit(int $n)
    {
        $this->limit = $n;
        return $this;
    }
}
