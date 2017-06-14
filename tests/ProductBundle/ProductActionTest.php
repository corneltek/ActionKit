<?php
use ActionKit\RecordAction\BaseRecordAction;
use ActionKit\ActionTemplate\UpdateOrderingRecordActionTemplate;
use ActionKit\ActionRunner;
use ActionKit\ActionGenerator;
use Maghead\Testing\ModelTestCase;

use ProductBundle\Action\CreateProduct;
use ProductBundle\Action\UpdateProduct;

use ProductBundle\Model\Product;
use ProductBundle\Model\ProductCollection;
use ProductBundle\Model\ProductSchema;

use ProductBundle\Model\ProductCategorySchema;
use ProductBundle\Model\ProductImageSchema;
use ProductBundle\Model\CategorySchema;
use ProductBundle\Model\ProductFeatureSchema;
use ProductBundle\Model\FeatureSchema;

use ProductBundle\Action\UpdateProductOrdering;

// use DateTime;

/**
 * RecordAction
 *
 * @group maghead
 */
class ProductActionTest extends ModelTestCase
{
    public function models()
    {
        return [
            new ProductSchema,
            new ProductImageSchema,
            new CategorySchema,
            new ProductCategorySchema,
            new ProductFeatureSchema,
            new FeatureSchema
        ];
    }

    public function createProductActionClass($type)
    {
        return BaseRecordAction::createCRUDClass(Product::class, $type);
    }

    protected function createProduct($name)
    {
        $ret = Product::create([ 'name' => $name ]);
        $this->assertTrue($ret->success);

        $p = Product::findByPrimaryKey($ret->key);
        $this->assertNotNull($p->id,'got created id');

        return $p;
    }

    public function testCreateRecordAction()
    {
        $class = BaseRecordAction::createCRUDClass(Product::class, 'Create');
        $this->assertEquals(CreateProduct::class, $class);

        $create = new $class(['name' => 'A']);
        $ret = $create->run();
        $this->assertTrue($ret,'success action');
    }

    public function testAsCreateAction()
    {
        $product = new Product;
        $this->assertNotNull($product, 'object created');
        $create = $product->asCreateAction([ 'name' => 'TestProduct' ]);
        $this->assertNotNull( $create->run() , 'action run' );

        $product = $create->getRecord();
        $this->assertInstanceOf('ProductBundle\\Model\\Product', $product);
        $this->assertNotNull($id = $product->id, 'product created');

        $delete = $product->asDeleteAction();
        $this->assertNotNull($delete->run());

        $product = Product::findByPrimaryKey($product->id);
        $this->assertFalse($product, 'product should be deleted.');
    }

    public function testAsUpdateForTimestampFormatWihtoutTimezone()
    {
        $p = Product::createAndLoad([ 'name' => 'B', 'updated_at' => '2017-06-11 14:00:00' ]);
        $this->assertNotNull($p, 'record created.');
        $this->assertEquals('2017-06-11 14:00:00', $p->updated_at);

        $a = $p->asUpdateAction([ 'updated_at' => '2000-02-02 02:02:02' ]);
        $ret = $a->run();
        $this->assertTrue($ret, 'Success action');

        $ret = $p->reload();
        $this->assertResultSuccess($ret);
        $this->assertInstanceOf(DateTime::class, $p->getUpdatedAt());
        $this->assertEquals('2000-02-02 02:02:02', $p->getUpdatedAt()->format("Y-m-d h:m:s") );
    }

    public function testUpdateRecordForTimestampFormatWithoutTimezone()
    {
        $p = Product::createAndLoad([ 'name' => 'B', 'updated_at' => '2017-06-11 14:00:00' ]);
        $this->assertNotNull($p, 'record created.');
        $this->assertEquals('2017-06-11 14:00:00', $p->updated_at);

        $ret = $p->update([ 'updated_at' => '2017-06-01 12:00:00' ]);
        $this->assertResultSuccess($ret);

        $ret = $p->reload();
        $this->assertResultSuccess($ret);
        $this->assertEquals('2017-06-01 12:00:00', $p->updated_at);

        $u = new UpdateProduct([
            'id' => $p->getKey(),
            'name' => 'C',
            'updated_at' => '2010-01-01 00:00:00' ,
        ], $p);
        $ret = $u->run();
        $this->assertTrue($ret, 'Success action');

        $p2 = Product::findByPrimaryKey($p->getKey());
        $this->assertNotNull($p2, 'record created.');

        $this->assertInstanceOf(DateTime::class, $p2->getUpdatedAt());
        $this->assertEquals('2010-01-01 00:00:00', $p2->getUpdatedAt()->format("Y-m-d H:i:s") );
    }

    public function testUpdateRecordWithLoadedRecordObject()
    {
        $ret = Product::create([ 'name' => 'B' ]);
        $this->assertResultSuccess($ret, 'record created.');

        $class = BaseRecordAction::createCRUDClass(Product::class, 'Update');
        $this->assertEquals(UpdateProduct::class, $class);

        $product = Product::load($ret->key);

        $update = new $class([ 'id' => $ret->key, 'name' => 'C' ], $product);
        $this->assertInstanceOf(UpdateProduct::class, $update);

        $ret = $update->run();
        $this->assertTrue($ret,'success action');

        // Verify the update.
        $product = Product::load([ 'name' => 'C' ]);
        $class = BaseRecordAction::createCRUDClass('ProductBundle\\Model\\Product', 'Delete');
        $this->assertNotNull($class);
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
        $this->assertCount(20, $products);

        $runner = new ActionRunner;

        $actionTemplate = new UpdateOrderingRecordActionTemplate;
        $actionTemplate->register($runner, 'UpdateOrderingRecordActionTemplate', [
            'namespace' => 'ProductBundle',
            'model'     => 'Product'   // model's name
        ]);

        $className = UpdateProductOrdering::class;

        $this->assertNotNull($pretreatment = $runner->getActionPretreatment($className));

        $generatedAction = $actionTemplate->generate($className, $pretreatment['arguments']);
        $this->assertNotNull($generatedAction);

        $tmp = $generatedAction->load();

        $updateOrdering = new $className([ 'list' => json_encode($idList) ]);
        $this->assertEquals($updateOrdering->getName(), 'UpdateProductOrdering');

        $ret = $updateOrdering->run();
        $result = $updateOrdering->getResult();
        $this->assertTrue($ret);

        $record = $updateOrdering->loadRecord(9);
        $this->assertEquals($record->ordering, 21 - 9);

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
        $this->assertTrue($create->run(), 'create action returns success.' );
        $this->assertResultSuccess($create->getRecord()->delete());
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
     * @expectedException \ActionKit\Exception\ActionException
     */
    public function testRecordActionWithActionException()
    {
        $class = $this->createProductActionClass('Update');
        $update = new $class;
    }
}


