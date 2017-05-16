<?php
use ActionKit\RecordAction\BaseRecordAction;
use ActionKit\ActionTemplate\UpdateOrderingRecordActionTemplate;
use ActionKit\ActionRunner;
use ActionKit\ActionGenerator;
use Maghead\Testing\ModelTestCase;
use ProductBundle\Model\Product;
use ProductBundle\Model\ProductCollection;
use ProductBundle\Model\ProductSchema;

/**
 * RecordAction
 *
 * @group maghead
 */
class ProductActionTest extends ModelTestCase
{
    public $driver = 'sqlite';

    public function models()
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
        $this->assertNotNull($p->id,'got created id');
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
        $this->assertNotNull($class);

        $create = new $class( array( 'name' => 'A' ), $product);
        $this->assertNotNull($create);

        $ret = $create->run();
        $this->assertNotNull($ret,'success action');

        $product->delete();
    }



    public function testAsCreateAction() {
        $product = new Product;
        $this->assertNotNull($product, 'object created');
        $create = $product->asCreateAction([ 'name' => 'TestProduct' ]);
        $this->assertNotNull( $create->run() , 'action run' );


        $product = $create->getRecord();
        $this->assertNotNull($id = $product->id, 'product created');

        $delete = $product->asDeleteAction();
        $this->assertNotNull($delete->run());

        $product = new Product( $id );
        $this->assertNotNull( ! $product->id, 'product should be deleted.');
    }

    public function testUpdateRecordAction()
    {
        $product = new Product;
        $this->assertNotNull($product);
        $ret = $product->create(array( 
            'name' => 'B',
        ));
        $this->assertNotNull($ret->success,'record created.');

        $class = BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product', 'Update');
        $this->assertNotNull($class);

        $update = new $class( array( 'id' => $product->id, 'name' => 'C' ), $product);
        $this->assertNotNull($update);

        $ret = $update->run();
        $this->assertNotNull($ret,'success action');

        $ret = $product->load(array( 'name' => 'C' ));
        $this->assertNotNull($ret->success);


        $class = BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product', 'Delete');
        $this->assertNotNull($class);

        $delete = new $class(array( 'id' => $product->id ), $product);
        $ret = $delete->run();
        $this->assertNotNull($ret);
    }


    public function testNestedFormRendering()
    {
        $class = BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product', 'Create');
        $create = new $class;
        $view = $create->asView();
        $this->assertNotNull($view);
        $html = $view->render();
        $this->assertNotNull($html);

        $dom = new DOMDocument;
        $dom->load($html);
        $this->assertNotNull($dom);
    }


    public function testUpdateOrdering()
    {
        $idList = array();
        foreach (range(1,20) as $num) {
            $product = $this->createProduct("Book $num");
            $this->assertNotNull($product);
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

        $this->assertNotNull($pretreatment = $runner->getActionPretreatment($className));

        $generatedAction = $actionTemplate->generate($className, $pretreatment['arguments']);
        $this->assertNotNull($generatedAction);

        $tmp = $generatedAction->load();

        $updateOrdering = new $className(array( 'list' => json_encode($idList) ));
        $this->assertEquals($updateOrdering->getName(), 'UpdateProductOrdering');
        $this->assertNotNull($updateOrdering->run());

        $result = $updateOrdering->loadRecord(9);
        $this->assertEquals($result->ordering, 21-9);

        $updateOrdering->mode = 99;
        $this->assertEquals(false, $updateOrdering->run());
    }

    public function testRecordUpdateWithExistingRecordObject()
    {
        $product = $this->createProduct('Book A');
        $this->assertNotNull($product);

        $class = $this->createProductActionClass('Update');
        $update = new $class(array('name' => 'Bar'), $product);
        $this->assertNotNull( $update->run() );
        $record = $update->getRecord();
        $this->assertNotNull($record->id);
        $this->assertEquals('Bar', $record->name);
    }

    public function testBulkRecordDelete()
    {
        $idList = array();
        foreach( range(1,20) as $num ) {
            $product = $this->createProduct("Book $num");
            $this->assertNotNull($product);
            $idList[] = $product->id;
        }

        $class = $this->createProductActionClass('BulkDelete');

        $bulkDelete = new $class(array( 'items' => $idList ));
        $this->assertNotNull( $bulkDelete->run(), 'items deleted' );
    }

    public function testBulkRecordCopy()
    {
        $idList = array();
        foreach( range(1,20) as $num ) {
            $product = $this->createProduct("Book $num");
            $this->assertNotNull($product);
            $idList[] = $product->id;
        }

        $class = $this->createProductActionClass('BulkCopy');

        $bulkCopy = new $class(array( 'items' => $idList ));
        $this->assertNotNull( $bulkCopy->run(), 'items copy' );
    }

    public function testRecordUpdate()
    {
        $product = $this->createProduct('Book A');
        $this->assertNotNull($product->id);
        $this->assertEquals(1, $product->id, 'product id');

        $class = $this->createProductActionClass('Update');
        $this->assertEquals('ProductBundle\\Action\\UpdateProduct', $class);

        $update = new $class(array('id' => $product->id , 'name' => 'Foo'));
        $success = $update->invoke();
        $this->assertTrue($success, $update->result);

        $record = $update->getRecord();
        $this->assertNotNull($record->id);
        $this->assertEquals('Foo', $record->name);
        $record->delete();
    }


    public function testRecordCreate()
    {
        $class = $this->createProductActionClass('Create');
        $create = new $class(array('name' => 'Foo'));
        $this->assertNotNull( $create->run(), 'create action returns success.' );
        $this->assertNotNull( $create->getRecord()->delete()->success );

    }

    public function testActionRelationship() 
    {
        $class = $this->createProductActionClass('Create');
        $create = new $class(array('name' => 'Foo'));

        $this->assertEquals(true, $create->hasRelation('product_categories'));
        $create->removeRelation('product_categories');
        $this->assertEquals(false, $create->hasRelation('product_categories'));
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


