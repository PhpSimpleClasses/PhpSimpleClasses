<?php

namespace Controllers;

use core\PSC;

class example extends PSC
{

    function exampleFunction()
    {
        echo "Hello!";
        $this->db->select('tableX', ['name', 'phone', 'address'])
            ->where('foo !=', 'bar')
            ->where('foz1', 'baz1')
            ->where('foz2', 'baz2');

        echo $this->db->getQuery();
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
