<?php
use ActionKit\ActionTemplate\SampleActionTemplate;


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
        $actionTemplate = new SampleActionTemplate('SampleActionTemplate');
        $template = $actionTemplate->generate('', '', array(
            'namespaceName' => 'Core',
            'actionName' => 'GrantAccess'
        ));
        ok( $template );

        $template->load();

        is( 'Core\\Action\\GrantAccess' , $template->class->getFullName() );
        ok( class_exists( 'Core\\Action\\GrantAccess' ) );
    }

}

