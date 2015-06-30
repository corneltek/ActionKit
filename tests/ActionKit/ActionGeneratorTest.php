<?php
use ActionKit\ActionRunner;
use ActionKit\ActionGenerator;
use ActionKit\RecordAction\BaseRecordAction;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionTemplate\FileBasedActionTemplate;

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
        $runner = new ActionRunner($generator);
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
        $runner = new ActionRunner($generator);
        $actionArgs = array(
            'namespace' => 'test',
            'model' => 'testModel',
            'types' => array(
                [ 'name' => 'Create'],
                [ 'name' => 'Update'],
                [ 'name' => 'Delete'],
                [ 'name' => 'BulkDelete']
            )
        );
        $runner->registerAction('RecordActionTemplate', $actionArgs);

        $className = 'test\Action\UpdatetestModel';

        $generatedAction = $generator->generateUnderDirectory('/tmp', 'RecordActionTemplate', $className, $actionArgs);
        $this->assertNotNull($generatedAction);

        $filePath = '/tmp' . DIRECTORY_SEPARATOR . $generatedAction->getPsrClassPath();
        $this->assertFileExists($filePath, $filePath);
        unlink($filePath);
    }

    public function testGeneratedAt()
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate());
        $runner = new ActionRunner($generator);
        $actionArgs = array(
            'namespace' => 'test',
            'model' => 'testModel',
            'types' => array(
                [ 'name' => 'Create'],
                [ 'name' => 'Update'],
                [ 'name' => 'Delete'],
                [ 'name' => 'BulkDelete']
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
        $runner = new ActionRunner($generator);
        $actionArgs = array(
            'namespace' => 'test',
            'model' => 'testModel',
            'types' => array(
                [ 'name' => 'Create'],
                [ 'name' => 'Update'],
                [ 'name' => 'Delete'],
                [ 'name' => 'BulkDelete']
            )
        );
        $runner->registerAction('RecordActionTemplate', $actionArgs);

        /*
        $template = $generator->getTemplate('RecordActionTemplate');
        $template->register($runner, 'RecordActionTemplate', $actionArgs);
         */

        $this->assertCount( 4, $runner->dynamicActions);

        $className = 'test\Action\UpdatetestModel';

        is(true, isset($runner->dynamicActions[$className]));

        $generatedAction = $generator->generate('RecordActionTemplate',
            $className,
            $runner->dynamicActions[$className]['actionArgs']);

        $generatedAction->load();

        ok(class_exists($className));
    }

    public function testFildBased()
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('FileBasedActionTemplate', new FileBasedActionTemplate());
        $template = $generator->getTemplate('FileBasedActionTemplate');
        $this->assertInstanceOf('ActionKit\ActionTemplate\ActionTemplate', $template);

        $runner = new ActionRunner($generator);
        $template->register($runner, 'FileBasedActionTemplate', array(
            'action_class' => 'User\\Action\\BulkUpdateUser',
            'template' => '@ActionKit/RecordAction.html.twig',
            'variables' => array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        ));
        is(1, count($runner->dynamicActions));

        $className = 'User\Action\BulkUpdateUser';

        is(true, isset($runner->dynamicActions[$className]));

        $generatedAction = $generator->generate('FileBasedActionTemplate',
            $className,
            $runner->dynamicActions[$className]['actionArgs']);

        $generatedAction->load();

        ok(class_exists($className));
    }

    public function testWithoutRegister()
    {
        $generator = new ActionGenerator();
        $generator->registerTemplate('FileBasedActionTemplate', new FileBasedActionTemplate());

        $className = 'User\Action\BulkDeleteUser';

        $generatedAction = $generator->generate('FileBasedActionTemplate',
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

