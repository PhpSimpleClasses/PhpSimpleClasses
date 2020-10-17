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
            ->where('foo != 2')
            ->where('foz1', 'baz1')
            ->orWhere('(foz2', 'baz2')
            ->where('foo != 2 OR bar = 2)');

        return $this->db->get(false); //Param false (run = false) to return query string

    }
}
