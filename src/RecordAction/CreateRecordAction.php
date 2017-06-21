<?php
namespace ActionKit\RecordAction;

abstract class CreateRecordAction extends BaseRecordAction
{
    public $enableLoadRecord = false;

    protected function create(array $args)
    {
        $this->recordResult = $ret = $this->recordClass::create($args);
        if ($ret->error) {
            $this->convertRecordValidation($ret);
            return $this->createError($ret);
        }
        $this->record = $this->recordClass::findByPrimaryKey($ret->key);
        $this->result->data($this->record->getData());
        return $this->createSuccess($ret);
    }

    protected function filterArguments(array $args)
    {
        if ($this->takeFields) {
            // take these fields only
            return array_intersect_key($args, array_fill_keys($this->takeFields, 1));
        } elseif ($this->filterOutFields) {
            return array_diff_key($args, array_fill_keys($this->filterOutFields, 1));
        }
        return $args;
    }

    /**
     * runValidate inherited from parent class.
     * */
    public function run()
    {
        $ret = $this->create($this->args);
        if ($ret === false) {
            return $ret;
        }
        if ($this->nested && ! empty($this->relationships)) {
            return $this->processSubActions();
        }
        return $ret;
    }

    public function successMessage($ret)
    {
        return $this->messagePool->translate('record_action.successful_create', $this->record->getLabel());
    }

    public function errorMessage($ret)
    {
        // XXX: should show exception message when error is found.
        if ($ret->exception) {
            return __('Can not create %1 record: %2', $this->record->getLabel(), $ret->exception->getMessage());
        }
        return $this->messagePool->translate('record_action.failed_create', $this->record->getLabel());
    }

    public function createSuccess($ret)
    {
        return $this->success($this->successMessage($ret), array(
            'id' => $this->record->id
        ));
    }

    public function createError($ret)
    {
        return $this->error($this->errorMessage($ret));
    }
}
