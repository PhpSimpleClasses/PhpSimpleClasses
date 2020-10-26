<?php

namespace Models;

use _core\PSC;

class example extends PSC
{

    public function __construct()
    {
        parent::__construct();
    }

    public function queryBuilderTest()
    {

        $this->db->select('tableX x', 'x.*, y.foo, y.other')
            ->join('tableY y', 'y.foo = x.foo', 'left')
            ->where('x.foo !=', '0')
            ->orWhere('y.bar', '10')
            ->limit(5)
            ->orderBy('y.foo ASC');

        return $this->db->get(false); //Param false (run = false) to return query string

    }
}
