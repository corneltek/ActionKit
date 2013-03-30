<?php

class CreateUserAction extends ActionKit\Action
{
    public function schema() 
    {
        $this->param('first_name')
            ->label('First name')
            ->renderAs('TextInput');

        $this->param('last_name')
            ->label('Last name')
            ->renderAs('TextInput');

        $this->param('role')
            ->label('Role')
            ->validValues(array( 'Admin', 'User' ))
            ->renderAs('SelectInput');
    }

    public function run() 
    {
        return $this->success('Created!');
    }
}

class StackViewTest extends PHPUnit_Framework_TestCase
{

    public function testNestedView()
    {
        $c = new \Product\Model\Category;
        $c->create(array( 'name' => 'Foo' ));
        $action = new \Product\Action\CreateProduct;
        $view = $action->asView('ActionKit\View\StackView',array(
            'no_form' => true,
            'no_layout' => true,
        ));
        $view->buildRelationalActionViewForExistingRecords('categories');
        ok( $view );
        $html = $view->getContainer()->render();
        ok( $html );

#          $dom = new DOMDocument;
#          $dom->load($html);

        $c->delete();
    }

    public function testBasicView()
    {
        $action = new CreateUserAction;
        ok($action);

        $view = new ActionKit\View\StackView($action);
        ok($view);

        $html = $view->render();
        ok($html);
        like('#<form\s#',$html);
        select_ok('.formkit-widget',8,$html);
        select_ok('.formkit-widget-text',2,$html);
        select_ok('.formkit-widget-select',1,$html);
        select_ok('.formkit-label',3,$html);
        select_ok('input[name=last_name]',true,$html);
        select_ok('input[name=first_name]',true,$html);
    }
}

