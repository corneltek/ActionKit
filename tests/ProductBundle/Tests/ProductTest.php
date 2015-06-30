<?php
use ActionKit\ActionRunner;
use ActionKit\ServiceContainer;
use ActionKit\ActionTemplate\FileBasedActionTemplate;
use ActionKit\ActionTemplate\UpdateOrderingRecordActionTemplate;

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



    // TODO: 改成用 UpdateOrderingRecordActionTemplate 來建立 UpdateOrderingRecordAction
    /**
     * @dataProvider orderingActionMapProvider
     */
    public function testProductUpdateOrderingActions($actionClass, $recordClass) 
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('FileBasedActionTemplate', new FileBasedActionTemplate());
        $generator->registerTemplate('UpdateOrderingRecordActionTemplate', new UpdateOrderingRecordActionTemplate());

        $runner = new ActionRunner($container);

        $runner->registerAction('UpdateOrderingRecordActionTemplate', array(
            'namespace' => 'ProductBundle',
            'record_class'     => $recordClass,   // model's name
        ));
        $action = $runner->createAction($actionClass);
        $this->assertNotNull($action);
    }

}



