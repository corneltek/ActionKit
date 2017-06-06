<?php

namespace ActionKit\ActionTemplate;

use ActionKit\ActionRunner;
use ActionKit\GeneratedAction;
use ActionKit\Testing\ActionTestCase;

class CodeGenActionTemplateTest extends ActionTestCase
{


    /**
     * @dataProvider classNameProvider
     */
    public function testCodeGenTemplateActionSuccessfulGenerationWithExtra($className)
    {
        $actionTemplate = new CodeGenActionTemplate();
        $runner = new ActionRunner;

        $actionTemplate->register($runner, 'CodeGenActionTemplate', array(
            'action_class' => $className,
            'use' => ['TestApp\Database'],
            'extends' => 'Action',
            'constants' => [
                'foo' => 123
            ],
        ));
        $this->assertCount(1, $runner->getPretreatments());
        $this->assertNotNull($pretreatment = $runner->getActionPretreatment($className));

        $generatedAction = $actionTemplate->generate($className, $pretreatment['arguments']);
        $this->assertRequireGeneratedAction($className, $generatedAction);
    }


    /**
     * @dataProvider classNameProvider
     */
    public function testCodeGenTemplateActionSuccessfulGeneration($className)
    {
        $actionTemplate = new CodeGenActionTemplate();
        $runner = new ActionRunner;

        $actionTemplate->register($runner, 'CodeGenActionTemplate', array(
            'action_class' => $className,
            'extends' => 'Action',
        ));
        $this->assertCount(1, $runner->getPretreatments());
        $this->assertNotNull($pretreatment = $runner->getActionPretreatment($className));

        $generatedAction = $actionTemplate->generate($className, $pretreatment['arguments']);
        $this->assertRequireGeneratedAction($className, $generatedAction);
    }

}

