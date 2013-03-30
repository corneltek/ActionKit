<?php
use LazyRecord\ModelTestCase;
use ActionKit\CRUD;

/**
 * RecordAction
 */
class ProductActionTest extends ModelTestCase
{
    public $driver = 'sqlite';

    public function getModels()
    {
        return array( 'Product\\Model\\ProductSchema' );
    }

    public function testCreateRecordAction()
    {
        $product = new Product\Model\Product;
        ok($product);

        $class = CRUD::generate('Product\\Model\\Product', 'Create');
        ok($class);

        $create = new $class( array( 'name' => 'A' ), $product);
        ok($create);

        $ret = $create->run();
        ok($ret,'success action');

        $product->delete();
    }

    public function testUpdateRecordAction()
    {
        $product = new Product\Model\Product;
        ok($product);
        $ret = $product->create(array( 
            'name' => 'B',
        ));
        ok($ret->success,'record created.');

        $class = CRUD::generate('Product\\Model\\Product', 'Update');
        ok($class);

        $update = new $class( array( 'id' => $product->id, 'name' => 'C' ), $product);
        ok($update);

        $ret = $update->run();
        ok($ret,'success action');

        $ret = $product->load(array( 'name' => 'C' ));
        ok($ret->success);


        $class = CRUD::generate('Product\\Model\\Product', 'Delete');
        ok($class);

        $delete = new $class(array( 'id' => $product->id ), $product);
        $ret = $delete->run();
        ok($ret);
    }


    public function testNestedFormRendering()
    {
        $class = CRUD::generate('Product\\Model\\Product', 'Create');
        $create = new $class;
        ok($create);
        $html = $create->asView()->render();
        ok($html);

        $dom = new DOMDocument;
        $dom->load($html);
        ok($dom);
    }

}


