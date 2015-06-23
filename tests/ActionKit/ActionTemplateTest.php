<?php

class ActionTemplate extends PHPUnit_Framework_TestCase
{
    protected $dynamicActionsWithTemplate = array();

    public function test()
    {
        $container = new ActionKit\ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate(new ActionKit\ActionTemplate\RecordActionTemplate);
        $template = $generator->loadTemplate('RecordActionTemplate'); 
        ok($template);

        $template->register($this, array(
            'namespace' => 'test',
            'model' => 'testModel',
            'types' => array('Create','Update','Delete','BulkDelete')
        ));
        is(4, count($this->dynamicActionsWithTemplate));

        $className = 'test\Action\UpdatetestModel';

        is(true, isset($this->dynamicActionsWithTemplate[$className]));

        $cacheFile = $generator->generate3('RecordActionTemplate', 
            $className, 
            $this->dynamicActionsWithTemplate[$className]['actionArgs']);

        require $cacheFile;
        ok( class_exists( $className ) );
    }

    // for action template register method
    public function registerWithTemplate($targetActionClass, $templateName, array $actionArgs = array())
    {
        $this->dynamicActionsWithTemplate[$targetActionClass] = array(
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