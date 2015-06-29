<?php
use ActionKit\ActionRunner;

class ProductBundleTest extends PHPUnit_Framework_TestCase
{

    // TODO: 改成用 UpdateOrderingRecordActionTemplate 來建立 UpdateOrderingRecordAction
    public function testProductUpdateOrderingActions() {
        
        $container = new ActionKit\ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('FileBasedActionTemplate', new ActionKit\ActionTemplate\FileBasedActionTemplate());

        $runner = new ActionRunner($container);
        ok($runner, 'action runner');

        $sortActionClasses = [
            'ProductBundle\\Action\\UpdateProductImageOrdering' => 'ProductBundle\\Model\\ProductImage',
            'ProductBundle\\Action\\UpdateProductPropertyOrdering' =>  'ProductBundle\\Model\\ProductProperty',
            'ProductBundle\\Action\\UpdateProductLinkOrdering' => 'ProductBundle\\Model\\ProductLink',
            'ProductBundle\\Action\\UpdateProductProductOrdering' => 'ProductBundle\\Model\\ProductProduct',
            'ProductBundle\\Action\\UpdateProductSubsectionOrdering' => 'ProductBundle\\Model\\ProductSubsection',
        ];


        foreach( $sortActionClasses as $actionClass => $recordClass ) {
            $runner->registerAction('FileBasedActionTemplate', array(
                'action_class' => $actionClass, 
                'template' => '@ActionKit/RecordAction.html.twig', 
                'variables' => [
                    'base_class' => 'ActionKit\\RecordAction\\UpdateOrderingRecordAction',
                    'record_class' => $recordClass,
                ]
            ));
        }
        
        foreach( $sortActionClasses as $actionClass => $recordClass ) {
            $action = $runner->createAction($actionClass);
            ok($action, $actionClass);
        }
    }


    public function testProductRecordActions()
    {


        

        
    }
}



