<?php
namespace ActionKit\ActionTemplate;
use ActionKit\Exception\RequiredConfigKeyException;
use ActionKit\ActionRunner;

/**
 *  Update Ordering Record Action Template Synopsis
 *
 *    $actionTemplate = new UpdateOrderingRecordActionTemplate; 
 *    $runner = new ActionKit\ActionRunner;
 *    $actionTemplate->register($runner, 'UpdateOrderingRecordActionTemplate', array(
 *        'namespace' => 'test2',
 *        'model' => 'Test2Model',   // model's name
 *    ));
 *
 *    $className = 'test2\Action\SortTest2Model';
 *    $actionArgs = $runner->pretreatments[$className]['actionArgs'];
 *    $generatedAction = $actionTemplate->generate($className, $actionArgs);
 *
 *    $generatedAction->load();
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


        if (!isset($options['model'])) {
            if (isset($options['record_class'])) {

                $nslist = explode("\\Model\\",$options['record_class']);
                $options['model'] = $nslist[1];

                if (!isset($options['namespace'])) {
                    $options['namespace'] = $nslist[0];
                }

            } else {
                throw new RequiredConfigKeyException('model', 'required for creating record actions');
            }
        }

        if (!isset($options['namespace'])) {
            throw new RequiredConfigKeyException('namespace', 'namespace');
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
