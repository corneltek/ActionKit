<?php
namespace ActionKit\ActionTemplate;
use ActionKit\ActionRunner;
use ActionKit\ActionTemplate\CodeGenActionTemplate;
use ActionKit\GeneratedAction;
use ClassTemplate\TemplateClassFile;


class RecordActionTemplate extends CodeGenActionTemplate
{

    /**
     * @synopsis
     *
     *    $template->register($runner, array(
     *        'namespace' => 'test',
     *        'model' => 'testModel',   // model's name
     *        'types' => array('Create','Update','Delete','BulkDelete')
     *    ));
     */
    public function register(ActionRunner $runner, $asTemplate, array $options = array())
    {
        if (!isset($options['namespace'])) {
            throw new RequiredConfigKeyException('namespace', 'namespace of the generated action');
        }
        if (!isset($options['model'])) {
            throw new RequiredConfigKeyException('model', 'required for creating record actions');
        }
        if (! isset($options['types'])) {
            throw new RequiredConfigKeyException('types', 'types is an array of operation names for CRUD');
        }

        foreach ( (array) $options['types'] as $type ) {
            $actionClass = $options['namespace'] . '\\Action\\' . $type['name'] . $options['model'];
            $properties = ['recordClass' => $options['namespace'] . "\\Model\\" . $options['model']];
            $traits = array();
            if (isset($type['allowedRoles'])) {
                $properties['allowedRoles'] = $type['allowedRoles'];
                $traits = ['ActionKit\ActionTrait\RoleChecker'];
            }
            $configs = [
                'extends' => "\\ActionKit\\RecordAction\\{$type['name']}RecordAction",
                'properties' => $properties,
                'traits' => $traits,
            ];
            
            $runner->register($actionClass, $asTemplate, $configs);
        }
    }


    /**
     * @synopsis
     *
     *    $generatedAction = $template->generate('test\Action\UpdatetestModel',
     *       [
     *           'extends' => "\\ActionKit\\RecordAction\\CreateRecordAction",  
     *           'properties' => [
     *               'recordClass' => "test\\testModel\\$modelName",    // $ns\\Model\\$modelName
     *           ],
     *           'getTemplateClass' => true  // return TemplateClassFile directly
     *       ]
     *    );
     */
    public function generate($actionClass, array $options = array())
    {
        $templateClassFile = new TemplateClassFile($actionClass);

        // General use statement
        $templateClassFile->useClass('\\ActionKit\\Action');
        $templateClassFile->useClass('\\ActionKit\\RecordAction\\BaseRecordAction');

        $this->initGenericClassWithOptions($templateClassFile, $options);
        $code = $templateClassFile->render();
        return new GeneratedAction($actionClass, $code, $templateClassFile);
    }
}


