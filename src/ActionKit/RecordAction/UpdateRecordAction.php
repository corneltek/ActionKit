<?php
namespace ActionKit\RecordAction;

abstract class UpdateRecordAction
    extends BaseRecordAction
{
    const TYPE = 'update';

    public $loadByArray = false;

    public $enableLoadRecord = true;

    public function loadRecord($args) {
        if ( ! isset($args['id']) && ! $this->loadByArray ) {
            return $this->error(_('Updating record requires an ID'));
        }
        if ( $this->loadByArray ) {
            $ret = $this->record->load($args);
        } elseif ( isset($args['id']) ) {
            $ret = $this->record->load( $args['id'] );
        } else {
            return $this->error( _('Require an ID to update record.') );
        }
        if ( ! $ret->success ) {
            return $this->error(__('Load Error: %1', $ret->message));
        }
        if ( ! $this->record->id ) {
            return $this->recordNotFound();
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
        if ( $error ) {
            $this->result->error( _('Validation Error') );
            return false;
        }
        return true;
    }

    public function recordNotFound()
    {
        return $this->error( __("%1 not found, can not update.", $this->record->getLabel()  ) );
    }

    public function successMessage($ret)
    {
        return __('%1 Record is updated.', $this->record->getLabel() );
    }

    public function errorMessage($ret)
    {
        if (!empty($ret->validations)) {
            return __('%1 validation failed.', $this->record->getLabel());
        }
        return __('%1 update failed.', $this->record->getLabel());
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
