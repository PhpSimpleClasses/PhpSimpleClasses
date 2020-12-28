<?php

namespace Controllers;

use _core\PSC;

class example extends PSC
{

    public function __construct()
    {
        parent::__construct();
    }


    public function postTest(){
        echo '<pre>';
        print_r($_POST);
    }
    public function getTest(){
        echo ':D';
    }

    public function exampleFunction()
    {
        $exampleModel = new \Models\example;

        $data['query'] = $exampleModel->queryBuilderTest();
        $data['description'] = 'This is a DB query example using query builder:';

        $data['title'] = 'PHP Simple Classes';
        $this->load('Views/example', $data);
    }

    public function api()
    {
        $this->json([
            "this" => "is",
            "a" => "test"
        ]);
    }

    public function paramsFunc($paramA, $paramB)
    {
        echo "Your params: $paramA and $paramB";
    }

    public function paramsFunc2($paramA, $paramB)
    {
        echo "Your params to this other function: $paramA and $paramB";
    }
}
