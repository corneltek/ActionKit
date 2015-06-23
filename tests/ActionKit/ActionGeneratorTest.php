<?php

class ActionGeneratorTest extends PHPUnit_Framework_TestCase
{


    public function testCRUDClassFromBaseRecordAction()
    {
        $class = ActionKit\RecordAction\BaseRecordAction::createCRUDClass( 'App\Model\Post' , 'Create' );
        ok($class);
        is('App\Action\CreatePost', $class);
    }

    public function testActionClassGenerator()
    {
        $g = new ActionKit\ActionGenerator;
        $template = $g->generateActionClassCode('Core','GrantAccess');
        ok( $template );

        $template->load();

        is( 'Core\\Action\\GrantAccess' , $template->class->getFullName() );
        ok( class_exists( 'Core\\Action\\GrantAccess' ) );
    }

}

