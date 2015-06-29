<?php
use ActionKit\RecordAction\BaseRecordAction;
use LazyRecord\Testing\ModelTestCase;
use ActionKit\ActionTemplate\UpdateOrderingRecordActionTemplate;

class RecordActionTest extends ModelTestCase
{
    public function getModels()
    {
        return array( 'Product\\Model\\ProductSchema' );
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
        return BaseRecordAction::createCRUDClass('Product\\Model\\Product',$type);
    }

    public function testRecordCreate()
    {
        $class = $this->createProductActionClass('Create');
        $create = new $class(array('name' => 'Foo'));
        ok( $create->run(), 'create action returns success.' );

        is(true, $create->hasRelation('product_categories'));
        $create->removeRelation('product_categories');
        is(false, $create->hasRelation('product_categories'));

        $create->createSubAction();

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

        $result = $update->loadRecord(['id' => $product->id]);
        is(true, $result);

        $update->args = array('id' => $product->id , 'name' => 'Bar');
        $result = $update->invoke();
        is(true, $result);

        $record->delete();
    }

    /**
    *   @expectedException  ActionKit\Exception\ActionException
    */
    public function testRecordActionWithActionException()
    {
        $class = $this->createProductActionClass('Update');
        $update = new $class(array());
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

    public function testUpdateOrdering()
    {
        $idList = array();
        foreach( range(1,20) as $num ) {
            $product = $this->createProduct("Book $num");
            ok($product);
            $idList[] = ['record' => $product->id, 'ordering' => 21 - $num];
        }

        $actionTemplate = new UpdateOrderingRecordActionTemplate;
        $runner = new ActionKit\ActionRunner;
        $actionTemplate->register($runner, 'UpdateOrderingRecordActionTemplate', array(
            'namespace' => 'Product',
            'model' => 'Product'   // model's name
        ));

        $className = 'Product\Action\UpdateProductOrdering';
        $actionArgs = $runner->dynamicActions[$className]['actionArgs'];
        $generatedAction = $actionTemplate->generate($className, $actionArgs);
        $generatedAction->load();

        $updateOrdering = new $className(array( 'list' => json_encode($idList) ));
        is($updateOrdering->getName(), 'UpdateProductOrdering');
        ok($updateOrdering->run());

        $result = $updateOrdering->loadRecord(9);
        is($result->ordering, 21-9);

        $updateOrdering->mode = 99;
        is(false, $updateOrdering->run());
    }
}
