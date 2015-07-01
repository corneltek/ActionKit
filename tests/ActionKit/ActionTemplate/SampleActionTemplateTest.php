<?php
use ActionKit\ActionRunner;
use ActionKit\ActionGenerator;
use ActionKit\RecordAction\BaseRecordAction;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionTemplate\FileBasedActionTemplate;
use ActionKit\ActionTemplate\SampleActionTemplate;

class SampleActionTemplateTest extends PHPUnit_Framework_TestCase
{
    public function testSampleActionTemplate()
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('SampleActionTemplate', new SampleActionTemplate());
        $runner = new ActionRunner($generator);
        // $runner->registerAction('SampleActionTemplate', array('action_class' => 'SampleAction'));
        $runner->getGenerator()->generate('SampleActionTemplate', 'SampleAction', [ 
            'namespace' => 'FooBar',
            'action_name' => 'CreateSample'
        ]);
    }
}

