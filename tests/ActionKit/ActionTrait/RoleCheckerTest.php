<?php
use ActionKit\ActionTrait\RoleChecker;
use Kendo\Acl\MultiRoleInterface;

class MyUser implements MultiRoleInterface 
{
    public $roles = array();

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    public function getRoles()
    {
        return $this->roles;
    }
}

class MyRoleChecker 
{

    use RoleChecker;

    public $allowedRoles;

    public function __construct(array $allowedRoles)
    {
        $this->allowedRoles = $allowedRoles;
    }

}

class RoleCheckerTest extends PHPUnit_Framework_TestCase
{
    public function testRoleAllowedRoleByString()
    {
        $checker = new MyRoleChecker(['admin']);
        $ret = $checker->currentUserCan('admin', 'run', []);
        $this->assertTrue($ret[0]);
    }

    public function testRoleDisallowedByString()
    {
        $checker = new MyRoleChecker(['admin']);
        $ret = $checker->currentUserCan('foo', 'run', []);
        $this->assertFalse($ret[0]);
    }

    public function testRoleAnonymousUserWithNull()
    {
        $checker = new MyRoleChecker(['admin']);
        $ret = $checker->currentUserCan(null, 'run', []);
        $this->assertFalse($ret[0]);
    }

    public function testRoleAllowedByUserObject()
    {
        $user = new MyUser(['admin']);
        $checker = new MyRoleChecker(['admin']);
        $ret = $checker->currentUserCan($user, 'run', []);
        $this->assertTrue($ret[0]);
    }

    public function testRoleDisallowedByUserObject()
    {
        $user = new MyUser(['user']);
        $checker = new MyRoleChecker(['admin']);
        $ret = $checker->currentUserCan($user, 'run', []);
        $this->assertFalse($ret[0]);
    }


    public function testGetAllowedRoles()
    {
        $checker = new MyRoleChecker(['admin']);
        $this->assertSame(['admin'],$checker->getAllowedRoles());
    }

}

