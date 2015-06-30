<?php
namespace ActionKit\ActionTemplate;
use ActionKit\ActionRunner;
use ActionKit\ActionTemplate\CodeGenActionTemplate;
use ActionKit\GeneratedAction;
use ActionKit\Exception\RequiredConfigKeyException;
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
        if (isset($options['use'])) {
            array_unshift($options['use'], '\\ActionKit\\Action', '\\ActionKit\\RecordAction\\BaseRecordAction');
        } else {
            $options['use'] = ['\\ActionKit\\Action', '\\ActionKit\\RecordAction\\BaseRecordAction'];
        }

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
                'use' => $options['use']
            ];
            
            $runner->register($actionClass, $asTemplate, $configs);
        }
    }
}


