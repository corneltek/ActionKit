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
        );
    }
    
    public function testRunnerWithUser()
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('RecordActionTemplate', new RecordActionTemplate);
        $runner = new ActionRunner($container);

        $runner->registerAutoloader();
        $runner->registerAction('RecordActionTemplate', array(
            'namespace' => 'User',
            'model' => 'User',
            'types' => array(
                ['name' => 'Create', 'allowedRoles' => ['user', 'admin'] ],
                ['name' => 'Update'],
                ['name' => 'Delete']
            )
        ));

        $runner->setCurrentUser('member');
        $result = $runner->run('User::Action::CreateUser',[
            'email' => 'foo@foo'
        ]);
        ok($result);
        is('Permission Denied.', $result->message);

        $runner->setCurrentUser('admin');
        $result = $runner->run('User::Action::CreateUser',[
            'email' => 'foo@foo'
        ]);
        is("User Record is created.", $result->message);

        $user = new TestUser;
        $user->roles = ['member', 'manager'];
        $runner->setCurrentUser($user);
        $result = $runner->run('User::Action::CreateUser',[
            'email' => 'foo@foo'
        ]);
        ok($result);
        is('Permission Denied.', $result->message);

        $user->roles = ['member', 'user'];
        $runner->setCurrentUser($user);
        $result = $runner->run('User::Action::CreateUser',[
            'email' => 'foo@foo'
        ]);
        ok($result);
        is("User Record is created.", $result->message);
    }
}
