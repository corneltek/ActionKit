<?php

namespace ActionKit;

use Maghead\Testing\ModelTestCase;
use OrderBundle\Model\OrderSchema;
use OrderBundle\Model\OrderItemSchema;
use OrderBundle\Model\Order;
use OrderBundle\Model\OrderItem;

use Maghead\Schema\RuntimeColumn;
use Magsql\Raw;

use ActionKit\Param\Param;
use DateTime;

class ColumnConvertTest extends ModelTestCase
{
    public function models()
    {
        return [ new OrderSchema, new OrderItemSchema ];
    }

    public function testConvertCurrentTimestampIntoPHPDateTimeObject()
    {
        $schema = Order::getSchema();
        $column = $schema->getColumn('updated_at');
        $this->assertInstanceOf(RuntimeColumn::class, $column);

        // assert the input
        $this->assertInstanceOf(Raw::class, $column->default);
        $this->assertEquals('CURRENT_TIMESTAMP', $column->default->__toString());

        $param = ColumnConvert::toParam($column);
        $this->assertInstanceOf(Param::class, $param);
        $this->assertInstanceOf(DateTime::class, $param->getDefaultValue()); 
    }

    public function testColumnConvert()
    {
        $schema = Order::getSchema();
        $this->assertNotNull($schema);
        $order = new Order;
        $action = ColumnConvert::convertSchemaToAction($schema, $order);
        $this->assertNotNull($action);
        $this->assertInstanceOf(Action::class, $action);
        $this->assertInstanceOf(RecordAction\BaseRecordAction::class, $action);
        $view = $action->asView(View\StackView::class);
        $this->assertNotNull($view);
        $this->assertInstanceOf(View\StackView::class, $view);
    }
}
