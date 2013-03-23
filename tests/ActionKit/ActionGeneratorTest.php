<?php

class ActionGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $g = new ActionKit\ActionGenerator;
        ok( $g );
        $code = $g->generateClassCode( 'App\Model\User' , 'Create' )->code;
        eval($code);
        ok( class_exists( 'App\\Action\\CreateUser' ) );
    }

    public function testCRUDClassFromBaseRecordAction()
    {
        $class = ActionKit\RecordAction\BaseRecordAction::createCRUDClass( 'App\Model\Post' , 'Create' );
        ok($class);
        is('App\Action\CreatePost', $class);
    }

    public function testActionClassGenerator()
    {
        $g = new ActionKit\ActionGenerator;
        $ret = $g->generateActionClassCode('Core','GrantAccess');
        ok( $ret );

        ok( $ret->code );
        ok( $ret->action_class );
        is( 'Core\\Action\\GrantAccess' , $ret->action_class );

        eval($ret->code);
        ok( class_exists( 'Core\\Action\\GrantAccess' ) );
    }

}

