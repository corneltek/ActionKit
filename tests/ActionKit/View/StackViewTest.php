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
        $c = new \ProductBundle\Model\Category;
        $c->create(array( 'name' => 'Foo' ));


        $action = new \ProductBundle\Action\CreateProduct;
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

        $resultDom = new DOMDocument;
        $resultDom->loadXML($html);

        $finder = new DomXPath($resultDom);

        $nodes = $finder->query("//form");
        is(1, $nodes->length);

        $nodes = $finder->query("//input");
        is(4, $nodes->length);

        $nodes = $finder->query("//*[contains(@class, 'formkit-widget')]");
        is(8, $nodes->length);

        $nodes = $finder->query("//*[contains(@class, 'formkit-widget-text')]");
        is(2, $nodes->length);

        $nodes = $finder->query("//*[contains(@class, 'formkit-label')]");
        is(3, $nodes->length);

        $nodes = $finder->query("//input[@name='last_name']");
        is(1, $nodes->length);

        $nodes = $finder->query("//input[@name='first_name']");
        is(1, $nodes->length);
    }
}

