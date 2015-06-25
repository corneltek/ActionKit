<?php
use ActionKit\ServiceContainer;
use ActionKit\ActionTemplate\CodeGenActionTemplate;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionRunner;

class ActionWithUser extends \LazyRecord\Testing\ModelTestCase
{
    public function getModels()
    {
        return array( 
            'User\Model\UserSchema',
            'Product\Model\ProductSchema',
        );
    }
    
    public function testRunAndJsonOutput()
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate());
        $runner = new ActionRunner($container);
        ok($runner);
        $runner->registerAutoloader();
        $runner->registerAction('RecordActionTemplate', array(
            'namespace' => 'User',
            'model' => 'User',
            'types' => array(
                ['name'=>'Create', 'allowedRoles'=>['user', 'admin']],
                ['name'=>'Update'],
                ['name'=>'Delete']
            )
        ));

        $result = $runner->run('User::Action::CreateUser',[ 
            'email' => 'foo@foo'
        ]);
        ok($result);
    }
}
