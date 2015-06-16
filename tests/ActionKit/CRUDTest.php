<?php
use ActionKit\CRUD;
use LazyRecord\Testing\ModelTestCase;

class CRUDTest extends ModelTestCase
{


    public function getModels()
    {
        return array( 
            'Product\Model\ProductSchema'
        );
    }


    public function CRUDTypeProvider()
    {
        return array( 
            array('Create'),
            array('Update'),
            array('Delete'),
        );
    }

    /**
     * @dataProvider CRUDTypeProvider
     */
    public function testProductCRUDGeneration($type)
    {
        $recordClass = 'Product\\Model\\Product';
        $actionClass = CRUD::generate($recordClass,$type);
        ok($actionClass);
        class_ok($actionClass);
        // ok(new $actionClass);
    }
}

