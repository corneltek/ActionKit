<?php
namespace ActionKit\RecordAction;

abstract class UpdateRecordAction
    extends BaseRecordAction
{
    const TYPE = 'update';

    /**
     * @var boolean load record by conditions defined in array.
     */
    public $loadByArray = false;

    public $enableLoadRecord = true;

    public function loadRecord($args)
    {
        if ( ! isset($args['id']) && ! $this->loadByArray ) {
            $msg = $this->messagePool->translate('record_action.primary_key_is_required');
            return $this->error($msg);
        }
        if ($this->loadByArray) {
            $ret = $this->record->load($args);
        } else if (isset($args['id'])) {
            $ret = $this->record->find( $args['id'] );
        } else {
            $msg = $this->messagePool->translate('record_action.primary_key_is_required');
            return $this->error($msg);
        }
        if (! $ret->success) {
            $msg = $this->messagePool->translate('record_action.load_failed');
            return $this->error($msg);
        }
        if (! $this->record->id) {
            $msg = $this->messagePool->translate('record_action.record_not_found', $this->record->getLabel());
            return $this->error($msg);
        }
        return true;
    }

    public function update( $args )
    {
        $record = $this->record;
        if ( ! $record->id ) {
            if ( false === $this->loadRecord($args) ) {
                return false;
            }
        }

        $ret = $record->update( $args );
        if (! $ret->success) {
            $this->convertRecordValidation( $ret );
            return $this->updateError( $ret );
        }

        return $this->updateSuccess($ret);
    }

    public function run()
    {
        $ret = $this->update( $this->args );
        if ( $this->nested && ! empty($this->relationships) ) {
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
            if ( $key === 'action' || $key === '__ajax_request' ) {
                continue;
            }
            if ( false === $this->validateparam( $key ) ) {
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
        return $this->success($this->successMessage($ret) , array( 'id' => $this->record->id ));
    }

    public function updateError($ret)
    {
        return $this->error($this->errorMessage($ret));
    }
}
