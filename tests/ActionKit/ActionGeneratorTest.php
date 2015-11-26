<?php
use ActionKit\ActionRunner;
use ActionKit\ActionGenerator;
use ActionKit\RecordAction\BaseRecordAction;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionTemplate\TwigActionTemplate;
use ActionKit\ActionTemplate\SampleActionTemplate;

class ActionGeneratorTest extends PHPUnit_Framework_TestCase
{


    // TODO: should be moved to BaseRecordActionTest
    public function testCRUDClassFromBaseRecordAction()
    {
        $class = BaseRecordAction::createCRUDClass( 'App\Model\Post' , 'Create' );
        ok($class);
        is('App\Action\CreatePost', $class);
    }


    /**
     * @expectedException ActionKit\Exception\RequiredConfigKeyException
     */
    public function testRequiredConfigKeyException()
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate());
        $runner = new ActionRunner([  'generator' => $generator ]);
        $runner->registerAction('RecordActionTemplate', array());
    }

    /**
     * @expectedException ActionKit\Exception\UndefinedTemplateException
     */
    public function testUndefinedTemplate()
    {
        $generator = new ActionGenerator();
        $template = $generator->getTemplate('UndefinedTemplate');
    }

    public function testTemplateGetter()
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate());
        $template = $generator->getTemplate('RecordActionTemplate');
        $this->assertInstanceOf('ActionKit\ActionTemplate\ActionTemplate', $template);
    }

    public function testGeneratedUnderDirectory()
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate());
        $runner = new ActionRunner([  'generator' => $generator ]);
        $actionArgs = array(
            'namespace' => 'test',
            'model' => 'testModel',
            'types' => array(
                [ 'prefix' => 'Create'],
                [ 'prefix' => 'Update'],
                [ 'prefix' => 'Delete'],
                [ 'prefix' => 'BulkDelete']
            )
        );
        $runner->registerAction('RecordActionTemplate', $actionArgs);

        $className = 'test\Action\UpdatetestModel';

        @mkdir('tmp', 0755, true);
        $generatedAction = $generator->generateUnderDirectory('tmp', 'RecordActionTemplate', $className, $actionArgs);
        $this->assertNotNull($generatedAction);

        $filePath = 'tmp' . DIRECTORY_SEPARATOR . $generatedAction->getPsrClassPath();
        $this->assertFileExists($filePath, $filePath);
        unlink($filePath);
    }

    public function testGeneratedAt()
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate());
        $runner = new ActionRunner([ 'generator' => $generator ]);
        $actionArgs = array(
            'namespace' => 'test',
            'model' => 'testModel',
            'types' => array(
                [ 'prefix' => 'Create'],
                [ 'prefix' => 'Update'],
                [ 'prefix' => 'Delete'],
                [ 'prefix' => 'BulkDelete']
            )
        );
        $runner->registerAction('RecordActionTemplate', $actionArgs);
        $className = 'test\Action\UpdatetestModel';
        $filePath = tempnam('/tmp', md5($className));
        $generatedAction = $generator->generateAt($filePath, 'RecordActionTemplate', $className, $actionArgs);
        $this->assertNotNull($generatedAction);
        $this->assertFileExists($filePath);
        unlink($filePath);
    }

    public function testRecordActionTemplate()
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate());
        $runner = new ActionRunner([  'generator' => $generator ]);
        $actionArgs = array(
            'namespace' => 'test',
            'model' => 'testModel',
            'types' => array(
                [ 'prefix' => 'Create'],
                [ 'prefix' => 'Update'],
                [ 'prefix' => 'Delete'],
                [ 'prefix' => 'BulkDelete']
            )
        );
        $runner->registerAction('RecordActionTemplate', $actionArgs);

        /*
        $template = $generator->getTemplate('RecordActionTemplate');
        $template->register($runner, 'RecordActionTemplate', $actionArgs);
         */

        $this->assertCount(4, $runner->getPretreatments());

        $className = 'test\Action\UpdatetestModel';

        $this->assertNotNull($pretreatment = $runner->getActionPretreatment($className));

        $generatedAction = $generator->generate('RecordActionTemplate',
            $className,
            $pretreatment['arguments']);

        $generatedAction->load();

        ok(class_exists($className));
    }

    public function testFildBased()
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('TwigActionTemplate', new TwigActionTemplate());
        $template = $generator->getTemplate('TwigActionTemplate');
        $this->assertInstanceOf('ActionKit\ActionTemplate\ActionTemplate', $template);

        $runner = new ActionRunner([  'generator' => $generator ]);
        $template->register($runner, 'TwigActionTemplate', array(
            'action_class' => 'User\\Action\\BulkUpdateUser',
            'template' => '@ActionKit/RecordAction.html.twig',
            'variables' => array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        ));


        $className = 'User\Action\BulkUpdateUser';

        $this->assertCount(1, $runner->getPretreatments());
        $this->assertNotNull($pretreatment = $runner->getActionPretreatment($className));

        $generatedAction = $generator->generate('TwigActionTemplate',
            $className,
            $pretreatment['arguments']);

        $generatedAction->load();

        ok(class_exists($className));
    }

    public function testWithoutRegister()
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('TwigActionTemplate', new TwigActionTemplate());

        $className = 'User\Action\BulkDeleteUser';

        $generatedAction = $generator->generate('TwigActionTemplate',
            $className,
            array(
                'template' => '@ActionKit/RecordAction.html.twig',
                'variables' => array(
                    'record_class' => 'User\\Model\\User',
                    'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
                )
            )
        );
        $generatedAction->load();
        ok( class_exists( $className ) );
    }

}

