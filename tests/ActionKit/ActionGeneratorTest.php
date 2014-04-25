<?php

class ActionGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $g = new ActionKit\ActionGenerator;
        ok( $g );
        $template = $g->generateClassCode( 'App\\Model\\User' , 'Create' );
        $template->load();
        ok( class_exists( '\\App\\Action\\CreateUser') );
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
        $template = $g->generateActionClassCode('Core','GrantAccess');
        ok( $template );

        $template->load();

        is( 'Core\\Action\\GrantAccess' , $template->class->getFullName() );
        ok( class_exists( 'Core\\Action\\GrantAccess' ) );
    }


    public function testGenerateMethod()
    {
        $g = new ActionKit\ActionGenerator;
        $code = $g->generate('ProductBundle\\Action\\CreateRecordProduct','@ActionKit/RecordAction.html.twig',array(
            'base_class' => 'ActionKit\\RecordAction\\CreateRecordAction',
            'record_class' => 'ProductBundle\\Model\\Product',
        ));
        ok( $code );
    }

}

