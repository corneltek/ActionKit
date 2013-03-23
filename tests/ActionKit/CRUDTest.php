<?php
use ActionKit\CRUD;

class CRUDTest extends PHPUnit_Framework_TestCase
{
    public function testCRUDGenerator()
    {
        // For full-qualified class name
        $class = CRUD::generate("ActionKit\\Model\\CRUDTest\\FooUser","Create");
        is('ActionKit\\Action\\CRUDTest\\CreateFooUser', $class);
        $a = new $class;
        ok($a);
        is('ActionKit\\Model\\CRUDTest\\FooUser',$a->recordClass);
    }

    public function testShortActionClassName()
    {
        $class = CRUD::generate("FooUser","Create");
        is('CreateFooUser', $class);

        $a = new $class;
        ok($a);

        is('FooUser',$a->recordClass);
    }
}

