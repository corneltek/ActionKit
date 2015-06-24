<?php
use ActionKit\ActionRunner;

class ProductBundleTest extends PHPUnit_Framework_TestCase
{


    public function testProductSortActions() {
        
        $container = new ActionKit\ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('FileBasedActionTemplate', new ActionKit\ActionTemplate\FileBasedActionTemplate());

        $runner = new ActionRunner($container);
        ok($runner, 'action runner');

        $sortActionClasses = [
            'ProductBundle\\Action\\SortProductImage' => 'ProductBundle\\Model\\ProductImage',
            'ProductBundle\\Action\\SortProductProperty' =>  'ProductBundle\\Model\\ProductProperty',
            'ProductBundle\\Action\\SortProductLink' => 'ProductBundle\\Model\\ProductLink',
            'ProductBundle\\Action\\SortProductProduct' => 'ProductBundle\\Model\\ProductProduct',
            'ProductBundle\\Action\\SortProductSubsection' => 'ProductBundle\\Model\\ProductSubsection',
        ];


        foreach( $sortActionClasses as $actionClass => $recordClass ) {
            $runner->registerAction('FileBasedActionTemplate', array(
                'targetClassName' => $actionClass, 
                'templateName' => '@ActionKit/RecordAction.html.twig', 
                'variables' => [
                    'base_class' => 'ActionKit\\RecordAction\\SortRecordAction',
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



