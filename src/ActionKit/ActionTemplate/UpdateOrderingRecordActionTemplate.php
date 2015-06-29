<?php
namespace ActionKit\ActionTemplate;
use ActionKit\ActionRunner;

/**
 *  Update Ordering Record Action Template Synopsis
 *
 *      $actionTemplate = new UpdateOrderingRecordActionTemplate; 
 *      $runner = new ActionKit\ActionRunner;
 *      $actionTemplate->register($runner, 'SortRecordActionTemplate', array(
 *          'namespace' => 'test2',
 *          'model' => 'Test2Model',   // model's name
 *      ));
 *
 *       $className = 'test2\Action\SortTest2Model';
 *       $actionArgs = $runner->dynamicActions[$className]['actionArgs'];
 *       $generatedAction = $actionTemplate->generate($className, $actionArgs);
 *
 *       $generatedAction->load();
 *
 */
class UpdateOrderingRecordActionTemplate extends RecordActionTemplate
{
    public function register(ActionRunner $runner, $asTemplate, array $options = array())
    {
        if (isset($options['use'])) {
            array_unshift($options['use'], '\\ActionKit\\Action', '\\ActionKit\\RecordAction\\BaseRecordAction');
        } else {
            $options['use'] = ['\\ActionKit\\Action', '\\ActionKit\\RecordAction\\BaseRecordAction'];
        }

        if (!isset($options['namespace'])) {
            throw new RequiredConfigKeyException('namespace', 'namespace');
        }
        if (!isset($options['model'])) {
            throw new RequiredConfigKeyException('model', 'required for creating record actions');
        }

        $actionClass = $options['namespace'] . '\\Action\\Update' . $options['model'] . 'Ordering';
        $runner->register($actionClass, $asTemplate, [
            'extends' => "\\ActionKit\\RecordAction\\UpdateOrderingRecordAction",
            'properties' => [
                'recordClass' => $options['namespace'] . "\\Model\\" . $options['model'],
            ]
        ]);
    }
}
