<?php
use ActionKit\ActionRunner;
use ActionKit\ActionRequest;
use ActionKit\Testing\ActionTestCase;
use ActionKit\ServiceContainer;
use ActionKit\ActionTemplate\TwigActionTemplate;
use ActionKit\ActionTemplate\UpdateOrderingRecordActionTemplate;
use ActionKit\Testing\ActionTestAssertions;
use OrderBundle\Model\Order;
use OrderBundle\Model\OrderCollection;
use OrderBundle\Model\OrderSchema;

use OrderBundle\Model\OrderItem;
use OrderBundle\Model\OrderItemCollection;
use OrderBundle\Model\OrderItemSchema;

use Maghead\Testing\ModelTestCase;
use ActionKit\RecordAction\BaseRecordAction;
use ActionKit\RecordAction\CreateRecordAction;
use ActionKit\RecordAction\UpdateRecordAction;
use ActionKit\RecordAction\DeleteRecordAction;

/**
 * @group maghead
 */
class OrderItemTest extends ModelTestCase
{
    use ActionTestAssertions;

    public function models()
    {
        return [new OrderSchema, new OrderItemSchema];
    }

    public function testCreateWithoutPrimaryKeyValue()
    {

        $schema = OrderItem::getSchema();
        $this->assertEquals('id', $schema->primaryKey);

        $primaryKeyColumn = $schema->getColumn('id');
        $this->assertNotNull($primaryKeyColumn);

        $this->assertTrue($primaryKeyColumn->autoIncrement, 'primary key is a auto-increment column');

        $orderRet = Order::create([ 'amount' => 100 ]);
        $orderItem = new OrderItem;
        $create = $orderItem->asCreateAction(['quantity' => 3, 'subtotal' => 120, 'order_id' => $orderRet->key ]);
        $this->assertNotNull($create);
        $this->assertInstanceOf(BaseRecordAction::class, $create);
        $this->assertInstanceOf(CreateRecordAction::class, $create);

        $success = $create();
        $this->assertTrue($success, 'Should be able to create without primary key value.');
    }

    public function testUpdateQuantityTo3()
    {
        
        $schema = OrderItem::getSchema();
        $this->assertEquals('id', $schema->primaryKey);

        $primaryKeyColumn = $schema->getColumn('id');
        $this->assertNotNull($primaryKeyColumn);
        $this->assertTrue($primaryKeyColumn->autoIncrement, 'primary key is a auto-increment column');

        $orderRet = Order::create([ 'amount' => 100 ]);
        $orderItem = new OrderItem;
        $create = $orderItem->asCreateAction([ 'quantity' => 3, 'subtotal' => 120, 'order_id' => $orderRet->key ]);
        $this->assertNotNull($create);
        $this->assertInstanceOf(BaseRecordAction::class, $create);
        $this->assertInstanceOf(CreateRecordAction::class, $create);

        $success = $create();
        $this->assertTrue($success, 'Should be able to create without primary key value.');

        $orderItem = $create->getRecord();
        $update = $orderItem->asUpdateAction(['quantity' => 3]);
        $this->assertNotNull($update);
        $this->assertInstanceOf(BaseRecordAction::class, $update);
        $this->assertInstanceOf(UpdateRecordAction::class, $update);

        $result = $update();
        $this->assertTrue($result);
    }


    public function testUpdateRequiredFieldWithNullValue()
    {
        $schema = OrderItem::getSchema();
        $this->assertEquals('id',$schema->primaryKey);

        $primaryKeyColumn = $schema->getColumn('id');
        $this->assertNotNull($primaryKeyColumn);
        $this->assertTrue($primaryKeyColumn->autoIncrement, 'primary key is a auto-increment column');

        $orderRet = Order::create([ 'amount' => 100 ]);
        $orderItem = new OrderItem;

        $create = $orderItem->asCreateAction([ 'quantity' => 3, 'subtotal' => 120, 'order_id' => $orderRet->key ]);
        $this->assertNotNull($create);
        $this->assertInstanceOf(BaseRecordAction::class, $create);
        $this->assertInstanceOf(CreateRecordAction::class, $create);

        $success = $create();
        $this->assertTrue($success, 'Should be able to create without primary key value.');

        $record = $create->getRecord();
        $this->assertNotNull($record);
        $this->assertNotEmpty($record->getData());

        $update = $record->asUpdateAction(['quantity' => null]);
        $this->assertNotNull($update);
        $this->assertInstanceOf(BaseRecordAction::class, $update);
        $this->assertInstanceOf(UpdateRecordAction::class, $update);

        $success = $update();
        $this->assertFalse($success, 'Should not be able to update required field with null value.');
    }
}
