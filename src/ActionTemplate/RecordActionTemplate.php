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
     *        'allowed_roles' => array('admin', 'manager'), 
     *        'types' => [
     *            ['prefix' => 'Create', 'allowed_roles' => ['user', 'admin'] ],
     *            ['prefix' => 'Update'],
     *            ['prefix' => 'Delete']
     *        ]
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

        foreach ((array) $options['types'] as $type) {
            // re-define type
            if (is_string($type)) {
                $type = [ 'prefix' => $type ];
            }


            $actionClass = $options['namespace'] . '\\Action\\' . $type['prefix'] . $options['model'];
            $properties = ['recordClass' => $options['namespace'] . "\\Model\\" . $options['model']];
            $traits = array();
            if (isset($options['allowed_roles']) || isset($type['allowed_roles'])) {
                $properties['allowedRoles'] = isset($type['allowed_roles']) ? $type['allowed_roles'] : $options['allowed_roles'];
                $traits = ['ActionKit\\ActionTrait\\RoleChecker'];
            }
            $configs = [
                'extends' => "\\ActionKit\\RecordAction\\{$type['prefix']}RecordAction",
                'properties' => $properties,
                'traits' => $traits,
                'use' => $options['use']
            ];

            $runner->register($actionClass, $asTemplate, $configs);
        }
    }
}


