<?php
namespace ActionKit\RecordAction;

abstract class UpdateRecordAction extends BaseRecordAction
{
    /**
     * @var boolean load record by conditions defined in array.
     */
    public $loadByArray = false;

    public $enableLoadRecord = true;

    /**
     * Load the record and return the result.
     */
    protected function loadRecord(array $args)
    {
        $schema = $this->recordClass::getSchema();
        $primaryKey = $schema->getPrimaryKey();

        if (! isset($args[$primaryKey]) && ! $this->loadByArray) {
            $msg = $this->messagePool->translate('record_action.primary_key_is_required');
            return $this->error($msg);
        }

        if ($this->loadByArray) {
            $this->record = $this->recordClass::load($args);
        } elseif (isset($args[$primaryKey])) {
            $this->record = $this->recordClass::findByPrimaryKey($args[$primaryKey]);
        } else {
            $msg = $this->messagePool->translate('record_action.primary_key_is_required');

            return $this->error($msg);
        }

        if (!$this->record) {
            $msg = $this->messagePool->translate('record_action.record_not_found', $htis->record->getLabel());
            return $this->error($msg);
        }

        return true;
    }

    public function update(array $args)
    {
        if (!$this->record) {
            if (false === $this->loadRecord($args)) {
                return false;
            }
        }

        $ret = $this->record->update($args);
        if (! $ret->success) {
            $this->convertRecordValidation($ret);
            return $this->updateError($ret);
        }

        return $this->updateSuccess($ret);
    }

    public function run()
    {
        $ret = $this->update($this->args);
        if ($this->nested && ! empty($this->relationships)) {
            $ret = $this->processSubActions();
        }

        return $ret;
    }

    /**
     * TODO: seperate this to CRUD actions
     */
    public function runValidate()
    {
        // validate from args
        $error = false;
        foreach ($this->args as $key => $value) {
            /* skip action column */
            if ($key === 'action' || $key === '__ajax_request') {
                continue;
            }
            if (false === $this->validateparam($key)) {
                $error = true;
            }
        }
        if ($error) {
            $msg = $this->messagePool->translate('record_action.validation_error', $this->record->getLabel());
            $this->result->error($msg);
            return false;
        }
        return true;
    }

    protected function recordNotFound()
    {
        $msg = $this->messagePool->translate('record_action.record_not_found', $this->record->getLabel());
        return $this->error($msg);
    }

    public function successMessage($ret)
    {
        return $this->messagePool->translate('record_action.successful_update', $this->record->getLabel());
    }

    public function errorMessage($ret)
    {
        if (!empty($ret->validations)) {
            return $this->messagePool->translate('record_action.validation_error', $this->record->getLabel());
        }
        return $this->messagePool->translate('record_action.failed_update', $this->record->getLabel());
    }

    public function updateSuccess($ret)
    {
        return $this->success($this->successMessage($ret), array( 'id' => $this->record->id ));
    }

    public function updateError($ret)
    {
        return $this->error($this->errorMessage($ret));
    }
}
