<?php
namespace ActionKit\RecordAction;

abstract class UpdateRecordAction
    extends BaseRecordAction
{
    const TYPE = 'update';

    public function update( $args )
    {
        $record = $this->record;
        if ( ! $record->id ) {
            // try to load record from argument id.
            if ( ! isset($args['id']) ) {
                return $this->error(_('Updating Record requires an ID'));
            }
            $record->load( $args['id'] );
            if ( ! $record->id ) {
                return $this->recordNotFound();
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
            if ( $key === 'action' || $key === '__ajax_request' )
                continue;

            $hasError = $this->validateparam( $key );
            if ( $hasError )
                $error = true;
        }
        if ( $error )
            $this->result->error( _('Validation Error') );

        return $error;
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
        return __('%1 update failed.', $this->record->getLabel() );
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
