<?php
use ActionKit\ServiceContainer;

use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\ActionRunner;

use OrderBundle\Model\OrderSchema;

class TestUser implements \Kendo\Acl\MultiRoleInterface
{
    public $roles;
    public function getRoles()
    {
        return $this->roles;
    }
}

/**
 * @group maghead
 */
class ActionWithUserTest extends \Maghead\Testing\ModelTestCase
{
    public function models()
    {
        return [ new OrderSchema ];
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
            'namespace' => 'OrderBundle',
            'model' => 'Order',
            'types' => array(
                ['prefix' => 'Create', 'allowed_roles' => ['user', 'admin'] ],
                ['prefix' => 'Update'],
                ['prefix' => 'Delete']
            )
        ));

        if($setUser) {
            $runner->setCurrentUser($roles);
        }
        $result = $runner->run('OrderBundle::Action::CreateOrder',[
            'quantity' => '1',
            'amount' => 100,
        ]);
        $this->assertNotNull($result);
        $this->assertEquals($resultType, $result->type);
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
            'namespace' => 'OrderBundle',
            'model' => 'Order',
            'types' => array(
                ['prefix' => 'Create', 'allowed_roles' => ['user', 'admin'] ],
                ['prefix' => 'Update'],
                ['prefix' => 'Delete']
            )
        ));

        $user = new TestUser;
        $user->roles = $roles;
        $runner->setCurrentUser($user);
        $result = $runner->run('OrderBundle::Action::CreateOrder',[
            'quantity' => '1',
            'amount' => 100,
        ]);
        $this->assertNotNull($result);
        $this->assertEquals($resultType, $result->type);
    }
}
