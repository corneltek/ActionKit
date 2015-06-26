<?php
use ActionKit\ServiceContainer;

use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionRunner;

class TestUser implements \Kendo\Acl\MultiRoleInterface
{
    public $roles;
    public function getRoles()
    {
        return $this->roles;
    }
}

class ActionWithUserTest extends \LazyRecord\Testing\ModelTestCase
{
    public function getModels()
    {
        return array( 
            'Order\Model\OrderSchema'
        );
    }

    public function userProvider()
    {
        return array(
            array('memeber', 'error', true),
            array('admin', 'success', true),
            array('admin', 'error', false),
        );
    }
    
    /**
     * @dataProvider userProvider
     */
    public function testRunnerWithSimpleUser($roles, $resultType, $setUser)
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate);
        $runner = new ActionRunner($container);
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

        if($setUser) {
            $runner->setCurrentUser($roles);
        }
        $result = $runner->run('Order::Action::CreateOrder',[
            'qty' => '1'
        ]);
        ok($result);
        is($resultType, $result->type);
    }


    public function roleProvider()
    {
        return array(
          array(['member', 'manager'], 'error'),
          array(['member', 'user'], 'success'),
        );
    }
    /**
     * @dataProvider roleProvider
     */
    public function testRunnerWithMultiRoleInterface($roles, $resultType)
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate);
        $runner = new ActionRunner($container);
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

        $user = new TestUser;
        $user->roles = $roles;
        $runner->setCurrentUser($user);
        $result = $runner->run('Order::Action::CreateOrder',[
            'qty' => '1'
        ]);
        ok($result);
        is($resultType, $result->type);
    }
}
