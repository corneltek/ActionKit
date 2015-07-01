<?php
use ActionKit\ActionRunner;
use ActionKit\ActionGenerator;
use ActionKit\RecordAction\BaseRecordAction;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionTemplate\FileBasedActionTemplate;
use ActionKit\ActionTemplate\SampleActionTemplate;
use ActionKit\Testing\ActionTestCase;

class FileBasedActionTemplateTest extends ActionTestCase
{
    public function failingArgumentProvider()
    {
        return [ 
            [ [] ],
            [ ['action_class' => 'FileApp\\Action\FooAction'] ],
            [ [
                'action_class' => 'FileApp\\Action\FooAction',
                'template' => '@ActionKit\RecordAction.html.twig',
            ] ],
        ];
    }

    /**
     * @dataProvider failingArgumentProvider
     * @expectedException ActionKit\Exception\RequiredConfigKeyException
     */
    public function testFileBasedActionTemplateWithException($arguments)
    {
        $actionTemplate = new FileBasedActionTemplate;
        $generator = new ActionGenerator();
        $generator->registerTemplate('FileBasedActionTemplate', $actionTemplate);

        $runner = new ActionRunner;
        $actionTemplate->register($runner, 'FileBasedActionTemplate', $arguments);

        $generator->generate('FileBasedActionTemplate', 'FileApp\Action\FooAction', $arguments);
    }


    public function testFileBasedActionTemplateWithTwigEnvironmentAndLoader()
    {
        $loader = new Twig_Loader_Filesystem([]);
        $loader->addPath('src/ActionKit/Templates', 'ActionKit');

        $env = new Twig_Environment($loader, array(
            'cache' => false,
        ));

        $actionTemplate = new FileBasedActionTemplate($loader, $env);

        $this->assertNotNull($actionTemplate->getTwigEnvironment());
        $this->assertNotNull($actionTemplate->getTwigLoader());

        $runner = new ActionRunner;
        $className = 'User\\Action\\BulkUpdateUser4';
        $actionTemplate->register($runner, 'FileBasedActionTemplate', array(
            'action_class' => $className,
            'template' => '@ActionKit/RecordAction.html.twig',
            'variables' => array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        ));
        $this->assertCount(1, $runner->getPretreatments());
        $this->assertNotNull($pretreatment = $runner->getActionPretreatment($className));

        $generatedAction = $actionTemplate->generate($className, $pretreatment['arguments']);
        $this->assertRequireGeneratedAction($className, $generatedAction);
    }



    public function testFileBasedActionTemplateWithTwigLoader()
    {
        $loader = new Twig_Loader_Filesystem([]);
        $loader->addPath('src/ActionKit/Templates', 'ActionKit');
        $actionTemplate = new FileBasedActionTemplate($loader);

        $runner = new ActionRunner;
        $className = 'User\\Action\\BulkUpdateUser3';
        $actionTemplate->register($runner, 'FileBasedActionTemplate', array(
            'action_class' => $className,
            'template' => '@ActionKit/RecordAction.html.twig',
            'variables' => array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        ));
        $this->assertCount(1, $runner->getPretreatments());
        $this->assertNotNull($pretreatment = $runner->getActionPretreatment($className));

        $generatedAction = $actionTemplate->generate($className, $pretreatment['arguments']);
        $this->assertRequireGeneratedAction($className, $generatedAction);
    }

    public function testFileBasedActionTemplate()
    {
        $actionTemplate = new FileBasedActionTemplate();
        $runner = new ActionRunner;
        $className = 'User\\Action\\BulkUpdateUser2';
        $actionTemplate->register($runner, 'FileBasedActionTemplate', array(
            'action_class' => $className,
            'template' => '@ActionKit/RecordAction.html.twig',
            'variables' => array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        ));
        $this->assertCount(1, $runner->getPretreatments());
        $this->assertNotNull($pretreatment = $runner->getActionPretreatment($className));

        $generatedAction = $actionTemplate->generate($className, $pretreatment['arguments']);
        $this->assertNotNull($generatedAction);
        $generatedAction->load();
        ok(class_exists($className));
    }
}



