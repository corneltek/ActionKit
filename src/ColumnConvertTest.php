<?php

namespace ActionKit;

use Maghead\Testing\ModelTestCase;
use OrderBundle\Model\OrderSchema;
use OrderBundle\Model\OrderItemSchema;
use OrderBundle\Model\Order;
use OrderBundle\Model\OrderItem;

class ColumnConvertTest extends ModelTestCase
{

    public function models()
    {
        return [ new OrderSchema, new OrderItemSchema ];
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


