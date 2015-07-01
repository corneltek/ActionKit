<?php
use ActionKit\ActionTemplate\SampleActionTemplate;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionTemplate\FileBasedActionTemplate;
use ActionKit\ActionTemplate\UpdateOrderingRecordActionTemplate;

class ActionTemplate extends PHPUnit_Framework_TestCase
{

    public function testRecordActionTemplate()
    {
        $actionTemplate = new RecordActionTemplate();
        $runner = new ActionKit\ActionRunner;
        $actionTemplate->register($runner, 'RecordActionTemplate', array(
            'namespace' => 'test2',
            'model' => 'test2Model',   // model's name
            'types' => array(
                [ 'name' => 'Create'],
                [ 'name' => 'Update'],
                [ 'name' => 'Delete'],
                [ 'name' => 'BulkDelete']
            )
        ));

        $className = 'test2\Action\Updatetest2Model';
        $this->assertCount(4, $runner->getPretreatments());
        $this->assertNotNull($pretreatment = $runner->getActionPretreatment($className));

        $generatedAction = $actionTemplate->generate($className, $pretreatment);
        $this->assertNotNull($generatedAction);
        $generatedAction->load();
        ok( class_exists( $className ) );
    }

}
