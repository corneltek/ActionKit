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

/**
 * @group lazyrecord
 */
use Maghead\Testing\ModelTestCase;
use ProductBundle\Model\ProductSchema;
use ProductBundle\Model\Category;
use ProductBundle\Action\CreateProduct;

class StackViewTest extends ModelTestCase
{

    public function models()
    {
        return array(new ProductSchema);
    }

    public function testNestedView()
    {
        $c = new Category;
        $c->create(array( 'name' => 'Foo' ));


        $action = new CreateProduct;
        $view = $action->asView('ActionKit\View\StackView',array(
            'no_form' => true,
            'no_layout' => true,
        ));
        $this->assertNotNull($view);

        $view->buildRelationalActionViewForExistingRecords('categories');
        $html = $view->getContainer()->render();
        $this->assertNotNull( $html );

#          $dom = new DOMDocument;
#          $dom->load($html);

        $c->delete();
    }

    public function testBasicView()
    {
        $action = new CreateUserAction;
        $this->assertNotNull($action);

        $view = new ActionKit\View\StackView($action);
        $this->assertNotNull($view);

        $html = $view->render();
        $this->assertNotNull($html);

        $resultDom = new DOMDocument;
        $resultDom->loadXML($html);

        $finder = new DomXPath($resultDom);

        $nodes = $finder->query("//form");
        $this->assertEquals(1, $nodes->length);

        $nodes = $finder->query("//input");
        $this->assertEquals(4, $nodes->length);

        $nodes = $finder->query("//*[contains(@class, 'formkit-widget')]");
        $this->assertEquals(8, $nodes->length);

        $nodes = $finder->query("//*[contains(@class, 'formkit-widget-text')]");
        $this->assertEquals(2, $nodes->length);

        $nodes = $finder->query("//*[contains(@class, 'formkit-label')]");
        $this->assertEquals(3, $nodes->length);

        $nodes = $finder->query("//input[@name='last_name']");
        $this->assertEquals(1, $nodes->length);

        $nodes = $finder->query("//input[@name='first_name']");
        $this->assertEquals(1, $nodes->length);
    }
}

