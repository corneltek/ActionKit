<?php
use ActionKit\ColumnConvert;

class ColumnConvertTest extends PHPUnit_Framework_TestCase
{
    public function testColumnConvert()
    {
        $order = new Order\Model\Order;
        $schema = $order->getSchema();
        $this->assertNotNull($schema);
        $action = ColumnConvert::convertSchemaToAction($schema, $order);
        $this->assertNotNull($action);
        $this->assertInstanceOf('ActionKit\Action', $action);
        $this->assertInstanceOf('ActionKit\RecordAction\BaseRecordAction', $action);
        $view = $action->asView('ActionKit\View\StackView');
        $this->assertNotNull($view);
        $this->assertInstanceOf('ActionKit\View\StackView', $view);
    }
}


