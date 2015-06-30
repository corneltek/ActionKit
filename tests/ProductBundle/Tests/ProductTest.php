<?php
use ActionKit\ActionRunner;
use ActionKit\ServiceContainer;

class ProductBundleTest extends PHPUnit_Framework_TestCase
{

    public function actionMapProvider() {
        return [
            ['ProductBundle\\Action\\UpdateProductImageOrdering', 'ProductBundle\\Model\\ProductImage'],
            ['ProductBundle\\Action\\UpdateProductPropertyOrdering', 'ProductBundle\\Model\\ProductProperty'],
            ['ProductBundle\\Action\\UpdateProductLinkOrdering', 'ProductBundle\\Model\\ProductLink'],
            ['ProductBundle\\Action\\UpdateProductProductOrdering', 'ProductBundle\\Model\\ProductProduct'],
            ['ProductBundle\\Action\\UpdateProductSubsectionOrdering', 'ProductBundle\\Model\\ProductSubsection'],
        ];
    }



    // TODO: 改成用 UpdateOrderingRecordActionTemplate 來建立 UpdateOrderingRecordAction
    /**
     * @dataProvider actionMapProvider
     */
    public function testProductUpdateOrderingActions($actionClass, $recordClass) 
    {
        $container = new ActionKit\ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('FileBasedActionTemplate', new ActionKit\ActionTemplate\FileBasedActionTemplate());

        $runner = new ActionRunner($container);

        $runner->registerAction('FileBasedActionTemplate', array(
            'action_class' => $actionClass, 
            'template' => '@ActionKit/RecordAction.html.twig', 
            'variables' => [
                'base_class' => 'ActionKit\\RecordAction\\UpdateOrderingRecordAction',
                'record_class' => $recordClass,
            ]
        ));
        $action = $runner->createAction($actionClass);

        $this->assertNotNull($action);
    }

}



