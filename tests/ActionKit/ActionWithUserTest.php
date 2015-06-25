<?php
use ActionKit\ServiceContainer;

use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionRunner;

class TestUser //implements \Kendo\Acl\MultiRoleInterface
{
    public $roles;
    public function getRoles()
    {
        return $this->roles;
    }
}

class ActionWithUser extends \LazyRecord\Testing\ModelTestCase
{
    public function getModels()
    {
        return array( 
            'User\Model\UserSchema',
            'Product\Model\ProductSchema',
            'Order\Model\OrderSchema'
        );
    }
    
    public function testRunnerWithUser()
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate);
        $runner = new ActionRunner($container);
        ok($runner);
        $runner->registerAutoloader();
        $runner->registerAction('RecordActionTemplate', array(
            'namespace' => 'Order',
            'model' => 'Order',
            'types' => array(
                ['name' => 'Create', 'allowedRoles' => ['user', 'admin'] ],
                ['name' => 'Update'],
                ['name' => 'Delete']
            )
        ));

        $runner->setCurrentUser('member');
        $result = $runner->run('Order::Action::CreateOrder',[
            'qty' => '1'
        ]);
        ok($result);
        is('error', $result->type);

        $runner->setCurrentUser('admin');
        $result = $runner->run('Order::Action::CreateOrder',[
            'qty' => '1'
        ]);
        is("success", $result->type);

        $user = new TestUser;
        $user->roles = ['member', 'manager'];
        $runner->setCurrentUser($user);
        $result = $runner->run('Order::Action::CreateOrder',[
            'qty' => '1'
        ]);
        ok($result);
        is('error', $result->type);

        $user->roles = ['member', 'user'];
        $runner->setCurrentUser($user);
        $result = $runner->run('Order::Action::CreateOrder',[
            'qty' => '1'
        ]);
        ok($result);
        is('success', $result->type);

    }
}
