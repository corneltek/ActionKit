<?php
use ActionKit\RecordAction\BaseRecordAction;
use ActionKit\ActionTemplate\UpdateOrderingRecordActionTemplate;
use ActionKit\ActionRunner;
use ActionKit\ActionGenerator;
use LazyRecord\Testing\ModelTestCase;
use ProductBundle\Model\Product;
use ProductBundle\Model\ProductCollection;
use ProductBundle\Model\ProductSchema;

/**
 * RecordAction
 */
class ProductActionTest extends ModelTestCase
{
    public $driver = 'sqlite';

    public function getModels()
    {
        return array( new ProductSchema );
    }

    public function setUp()
    {
        $products = new ProductCollection;
        foreach ($products as $product) {
            $product->delete();
        }
        parent::setUp();
    }

    public function tearDown()
    {
        $products = new ProductCollection;
        foreach ($products as $product) {
            $product->delete();
        }
        parent::tearDown();
    }

    public function createProductActionClass($type)
    {
        return BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product',$type);
    }

    public function createProduct( $name )
    {
        $p = new Product;
        $ret = $p->create(array( 
            'name' => $name
        ));
        $this->assertTrue($ret->success);
        ok($p->id,'got created id');
        return $p;
    }


    public function recordProvider() {
        return [ 
            [ new ProductBundle\Model\Product ],
        ];
    }


    /**
     * @dataProvider recordProvider
     */
    public function testCreateRecordAction($product)
    {
        $class = BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product', 'Create');
        ok($class);

        $create = new $class( array( 'name' => 'A' ), $product);
        ok($create);

        $ret = $create->run();
        ok($ret,'success action');

        $product->delete();
    }



    public function testAsCreateAction() {
        $product = new Product;
        ok($product, 'object created');
        $create = $product->asCreateAction([ 'name' => 'TestProduct' ]);
        ok( $create->run() , 'action run' );


        $product = $create->getRecord();
        ok($id = $product->id, 'product created');


        $delete = $product->asDeleteAction();
        ok($delete->run());

        $product = new Product( $id );
        ok( ! $product->id, 'product should be deleted.');
    }

    public function testUpdateRecordAction()
    {
        $product = new Product;
        ok($product);
        $ret = $product->create(array( 
            'name' => 'B',
        ));
        ok($ret->success,'record created.');

        $class = BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product', 'Update');
        ok($class);

        $update = new $class( array( 'id' => $product->id, 'name' => 'C' ), $product);
        ok($update);

        $ret = $update->run();
        ok($ret,'success action');

        $ret = $product->load(array( 'name' => 'C' ));
        ok($ret->success);


        $class = BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product', 'Delete');
        ok($class);

        $delete = new $class(array( 'id' => $product->id ), $product);
        $ret = $delete->run();
        ok($ret);
    }


    public function testNestedFormRendering()
    {
        $class = BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product', 'Create');
        $create = new $class;
        ok($create);
        $html = $create->asView()->render();
        ok($html);

        $dom = new DOMDocument;
        $dom->load($html);
        ok($dom);
    }


    public function testUpdateOrdering()
    {
        $idList = array();
        foreach (range(1,20) as $num) {
            $product = $this->createProduct("Book $num");
            ok($product);
            $idList[] = ['record' => $product->id, 'ordering' => 21 - $num];
        }
        $products = new ProductCollection;
        $this->assertEquals(20, $products->count());


        $actionTemplate = new UpdateOrderingRecordActionTemplate;
        $runner = new ActionKit\ActionRunner;
        $actionTemplate->register($runner, 'UpdateOrderingRecordActionTemplate', array(
            'namespace' => 'ProductBundle',
            'model'     => 'Product'   // model's name
        ));

        $className = 'ProductBundle\Action\UpdateProductOrdering';
        $actionArgs = $runner->pretreatments[$className]['arguments'];
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

    public function testBulkRecordCopy()
    {
        $idList = array();
        foreach( range(1,20) as $num ) {
            $product = $this->createProduct("Book $num");
            ok($product);
            $idList[] = $product->id;
        }

        $class = $this->createProductActionClass('BulkCopy');

        $bulkCopy = new $class(array( 'items' => $idList ));
        ok( $bulkCopy->run(), 'items copy' );
    }

    public function testRecordUpdate()
    {
        $product = $this->createProduct('Book A');
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


    public function testRecordCreate()
    {
        $class = $this->createProductActionClass('Create');
        $create = new $class(array('name' => 'Foo'));
        ok( $create->run(), 'create action returns success.' );
        ok( $create->getRecord()->delete()->success );

    }

    public function testActionRelationship() 
    {
        $class = $this->createProductActionClass('Create');
        $create = new $class(array('name' => 'Foo'));

        is(true, $create->hasRelation('product_categories'));
        $create->removeRelation('product_categories');
        is(false, $create->hasRelation('product_categories'));
    }

    public function testCreateSubAction()
    {
        $class = $this->createProductActionClass('Create');
        $create = new $class(array('name' => 'Foo'));

        /*
        $subaction = $create->createSubAction('product_categories', [ 
            'id' => 10, // an inexisting-record
        ]);
        $this->assertNotNull($subaction);
        */
    }

    /**
     * @expectedException  ActionKit\Exception\ActionException
     */
    public function testRecordActionWithActionException()
    {
        $class = $this->createProductActionClass('Update');
        $update = new $class(array());
    }

}


