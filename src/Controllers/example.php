<?php

namespace Controllers;

use core\PSC;

class example extends PSC
{

    function exampleFunction()
    {
        echo "Hello!<BR>";
        $this->db->select('tableX', 'x.*, y.foo, y.other')
            ->join('tableY y', 'y.foo = x.foo', 'left')
            ->where('foo != 2')
            ->where('foz1', 'baz1')
            ->orWhere('(foz2', 'baz2')
            ->where('foo != 2 OR bar = 2)');

        echo $this->db->get(false); //Param false (run = false) to return query string
    }

    function paramsFunc($paramA, $paramB)
    {
        echo "Your params: $paramA and $paramB";
    }

    function paramsFunc2($paramA, $paramB)
    {
        echo "Your params to this other function: $paramA and $paramB";
    }
}
