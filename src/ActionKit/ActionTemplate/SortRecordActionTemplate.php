<?php
namespace ActionKit\ActionTemplate;
use ActionKit\ActionRunner;

/**
 *  Sort Record Action Template Synopsis
 *
 *      $actionTemplate = new SortRecordActionTemplate; 
 *      $runner = new ActionKit\ActionRunner;
 *      $actionTemplate->register($runner, 'SortRecordActionTemplate', array(
 *          'namespace' => 'test2',
 *          'model' => 'Test2Model',   // model's name
 *          'types' => array('Sort')
 *      ));
 *
 *       $className = 'test2\Action\SortTest2Model';
 *       $actionArgs = $runner->dynamicActions[$className]['actionArgs'];
 *       $generatedAction = $actionTemplate->generate($className, $actionArgs);
 *
 *       $generatedAction->load();
 *
 */
class SortRecordActionTemplate extends RecordActionTemplate
{
    const MODE_INCREMENTALLY = 1;
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
                    'mode' => self::MODE_INCREMENTALLY,
                    'targetColumn' => 'ordering'
                ],
                'constants' => [
                    'MODE_INCREMENTALLY' => 1,
                    'MODE_BYDATE' => 2
                ],
                'traits' => [
                    'ActionKit\ActionTrait\RecordSorter'
                ]
            ]);
        }
    }
}
