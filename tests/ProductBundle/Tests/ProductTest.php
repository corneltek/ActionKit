<?php
use ActionKit\ActionRunner;
use ActionKit\ServiceContainer;
use ActionKit\ActionTemplate\TwigActionTemplate;
use ActionKit\ActionTemplate\UpdateOrderingRecordActionTemplate;
use ProductBundle\Action\CreateProductFile;
use ProductBundle\Action\CreateProductImage;

class ProductBundleTest extends PHPUnit_Framework_TestCase
{

    public function orderingActionMapProvider() {
        return [
            ['ProductBundle\\Action\\UpdateProductImageOrdering'      , 'ProductBundle\\Model\\ProductImage']      , 
            ['ProductBundle\\Action\\UpdateProductPropertyOrdering'   , 'ProductBundle\\Model\\ProductProperty']   , 
            ['ProductBundle\\Action\\UpdateProductLinkOrdering'       , 'ProductBundle\\Model\\ProductLink']       , 
            ['ProductBundle\\Action\\UpdateProductProductOrdering'    , 'ProductBundle\\Model\\ProductProduct']    , 
            ['ProductBundle\\Action\\UpdateProductSubsectionOrdering' , 'ProductBundle\\Model\\ProductSubsection'] , 
        ];
    }


    /**
     * @dataProvider orderingActionMapProvider
     */
    public function testProductUpdateOrderingActions($actionClass, $recordClass) 
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('TwigActionTemplate', new TwigActionTemplate());
        $generator->registerTemplate('UpdateOrderingRecordActionTemplate', new UpdateOrderingRecordActionTemplate());

        $runner = new ActionRunner($container);

        $runner->registerAction('UpdateOrderingRecordActionTemplate', array(
            'namespace' => 'ProductBundle',
            'record_class'     => $recordClass,   // model's name
        ));
        $action = $runner->createAction($actionClass);
        $this->assertNotNull($action);
    }

    public function testCreateProductImage()
    {
        $create = new CreateProductImage;
        $create->run();
    }

    public function testCreateProductFile()
    {
        $create = new CreateProductFile;
        $create->run();
    }
}



