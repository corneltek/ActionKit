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
use LazyRecord\Testing\ModelTestCase;
use ActionKit\RecordAction\CreateRecordAction;
use ActionKit\RecordAction\UpdateRecordAction;
use ActionKit\RecordAction\DeleteRecordAction;

/**
 * @group lazyrecord
 */
class OrderBundleTest extends ModelTestCase
{
    use ActionTestAssertions;

    public $driver = 'sqlite';

    public function getModels()
    {
        return array( new OrderSchema );
    }

    public function testGeneratedCreateAction()
    {
        $order = new Order;
        $schema = $order->getSchema();
        $this->assertEquals('id',$schema->primaryKey);

        $primaryKeyColumn = $schema->getColumn('id');
        $this->assertNotNull($primaryKeyColumn);

        var_dump( $primaryKeyColumn->autoIncrement ); 
        



        $create = $order->asCreateAction();
        $this->assertNotNull($create);
        $this->assertInstanceOf('ActionKit\\RecordAction\\BaseRecordAction', $create);
        $this->assertInstanceOf('ActionKit\\RecordAction\\CreateRecordAction', $create);
    }


}



