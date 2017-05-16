<?php
use ActionKit\ActionRunner;
use ActionKit\ActionGenerator;
use ActionKit\RecordAction\BaseRecordAction;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionTemplate\TwigActionTemplate;
use ActionKit\ActionTemplate\SampleActionTemplate;

class SampleActionTemplateTest extends \PHPUnit\Framework\TestCase
{

    public function failingArgumentProvider()
    {
        return [ 
            [ ['namespace' => 'FooBar'] ],
            [ ['action_name' => 'CreateSample'] ],
            [ [] ]
        ];
    }

    /**
     * @dataProvider failingArgumentProvider
     * @expectedException ActionKit\Exception\RequiredConfigKeyException
     */
    public function testSampleActionTemplateWithException($arguments)
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('SampleActionTemplate', new SampleActionTemplate());
        $generator->generate('SampleActionTemplate', 'SampleAction', $arguments);
    }

    public function testSampleActionTemplate()
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('SampleActionTemplate', new SampleActionTemplate());
        $runner = new ActionRunner([ 'generator' => $generator ]);
        // $runner->registerAction('SampleActionTemplate', array('action_class' => 'SampleAction'));
        $runner->getGenerator()->generate('SampleActionTemplate', 'SampleAction', [ 
            'namespace' => 'FooBar',
            'action_name' => 'CreateSample'
        ]);
    }
}

