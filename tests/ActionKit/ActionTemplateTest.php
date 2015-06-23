<?php

class ActionTemplate extends PHPUnit_Framework_TestCase
{
    protected $dynamicActions = array();

    public function testCodeGenBased()
    {
        $generator = new ActionKit\ActionGenerator(array( 'cache' => true ));
        $generator->registerTemplate(new ActionKit\ActionTemplate\RecordActionTemplate);
        $template = $generator->loadTemplate('RecordActionTemplate'); 
        ok($template);

        $runner = new ActionKit\ActionRunner;
        $template->register($runner, array(
            'namespace' => 'test',
            'model' => 'testModel',
            'types' => array('Create','Update','Delete','BulkDelete')
        ));
        is(4, count($runner->dynamicActions));

        $className = 'test\Action\UpdatetestModel';

        is(true, isset($runner->dynamicActions[$className]));

        $cacheFile = $generator->generate('RecordActionTemplate', 
            $className, 
            $runner->dynamicActions[$className]['actionArgs']);

        require $cacheFile;
        ok( class_exists( $className ) );
    }

    public function testTemplateBased()
    {
        $generator = new ActionKit\ActionGenerator(array( 'cache' => true ));
        $generator->registerTemplate(new ActionKit\ActionTemplate\FileBasedActionTemplate);
        $template = $generator->loadTemplate('FileBasedActionTemplate'); 
        ok($template);

        $runner = new ActionKit\ActionRunner;
        $template->register($runner, array(
            'targetClassName' => 'User\\Action\\BulkUpdateUser',
            'templateName' => '@ActionKit/RecordAction.html.twig',
            'variables' => array(
                'record_class' => 'User\\Model\\User',
                'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
            )
        ));
        is(1, count($runner->dynamicActions));

        $className = 'User\Action\BulkUpdateUser';

        is(true, isset($runner->dynamicActions[$className]));

        $cacheFile = $generator->generate('FileBasedActionTemplate', 
            $className, 
            $runner->dynamicActions[$className]['actionArgs']);

        require $cacheFile;
        ok( class_exists( $className ) );
    }

    public function testWithRegister()
    {
        $generator = new ActionKit\ActionGenerator(array( 'cache' => true ));
        $generator->registerTemplate(new ActionKit\ActionTemplate\FileBasedActionTemplate);
        
        $className = 'User\Action\BulkDeleteUser';

        $cacheFile = $generator->generate('FileBasedActionTemplate', 
            $className, 
            array(
                'template' => '@ActionKit/RecordAction.html.twig',
                'variables' => array(
                    'record_class' => 'User\\Model\\User',
                    'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction'
                )
            )
        );

        require $cacheFile;
        ok( class_exists( $className ) );
    }

    // for action template register method
    public function registerWithTemplate($targetActionClass, $templateName, array $actionArgs = array())
    {
        $this->dynamicActions[$targetActionClass] = array(
            'templateName' => $templateName,
            'actionArgs' => $actionArgs
        );
    }

    public function getClassCacheFile($className, $params = null)
    {
        $chk = $params ? md5(serialize($params)) : '';
        return $this->cacheDir . DIRECTORY_SEPARATOR . str_replace('\\','_',$className) . $chk . '.php';
    }
}