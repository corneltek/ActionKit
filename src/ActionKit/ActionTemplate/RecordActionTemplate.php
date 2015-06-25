<?php
namespace ActionKit\ActionTemplate;
use ActionKit\ActionRunner;
use ActionKit\ActionTemplate\CodeGenActionTemplate;


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
            throw new RequiredConfigKeyException('namespace', 'namespace');
        }
        if (!isset($options['model'])) {
            throw new RequiredConfigKeyException('model', 'required for creating record actions');
        }
        if (! isset($options['types'])) {
            throw new RequiredConfigKeyException('types', 'types is an array of operation names for CRUD');
        }

        foreach ( (array) $options['types'] as $type ) {
            $actionClass = $options['namespace'] . '\\Action\\' . $type . $options['model'];
            $runner->register($actionClass, $asTemplate, [
                'extends' => "\\ActionKit\\RecordAction\\{$type}RecordAction",
                'properties' => [
                    'recordClass' => $options['namespace'] . "\\Model\\" . $options['model'],
                ],
            ]);
        }
    }

}


