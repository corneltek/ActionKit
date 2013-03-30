<?php
use ActionKit\CRUD;
use LazyRecord\ModelTestCase;

class RecordActionTest extends ModelTestCase
{
    public function getModels()
    {
        return array( 
            'Product\Model\ProductSchema'
        );
    }

    public function createProduct( $name )
    {
        $p = new \Product\Model\Product;
        $ret = $p->create(array( 
            'name' => $name
        ));
        ok($ret->success,'record created.');
        ok($p->id,'got created id');
        return $p;
    }

    public function createProductActionClass($type)
    {
        return CRUD::generate('Product\Model\Product',$type);
    }

    public function testRecordCreate()
    {
        $class = $this->createProductActionClass('Create');
        $create = new $class(array('name' => 'Foo'));
        ok( $create->run(), 'create action returns success.' );

        ok( $create->getRecord()->delete()->success );
    }

    public function testRecordUpdate()
    {
        $product = $this->createProduct('Book A');
        ok($product);

        $class = $this->createProductActionClass('Update');
        $update = new $class(array('id' => $product->id , 'name' => 'Foo'));
        ok( $update->run() );
        $record = $update->getRecord();
        ok($record->id);
        is('Foo', $record->name);

        $record->delete();
    }

    public function testRecordUpdateWithExistingRecordObject()
    {
        $product = $this->createProduct('Book A');
        ok($product);

        $class = $this->createProductActionClass('Update');
        $update = new $class(array('name' => 'Bar'), $product);
        ok( $update->run() );
        $record = $update->getRecord();
        ok($record->id);
        is('Bar', $record->name);
    }

    public function testBulkRecordDelete()
    {
        $idList = array();
        foreach( range(1,20) as $num ) {
            $product = $this->createProduct("Book $num");
            ok($product);
            $idList[] = $product->id;
        }

        $class = $this->createProductActionClass('BulkDelete');
        $bulkDelete = new $class(array( 'items' => $idList ));
        ok( $bulkDelete->run(), 'items deleted' );
    }

}
