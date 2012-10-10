<?php
namespace ActionKit\RecordAction;

abstract class UpdateRecordAction 
    extends BaseRecordAction
{
    const TYPE = 'update';

    public function update( $args )
    {
        $id = (int) $args['id'];
        if( ! $id )
            return $this->error( _('Arguments Error') );

        $record = $this->record;
        $record->load( $id );
        if( ! $record->id )
            return $this->recordNotFound();

        $ret = $record->update( $args );
        if( ! $ret->success ) {
            $this->convertRecordValidation( $ret );
            return $this->updateError( $ret );
        }
        return $this->updateSuccess($ret);
    }

    public function run() 
    { 
        $ret = $this->update( $this->args );
        if( $this->nested && ! empty($this->relationships) ) {
            $ret = $this->processSubActions();
        }
        return $ret;
    }

    /**
     * TODO: seperate this to CRUD actions 
     */
    function runValidate()
    {
        // validate from args 
        $error = false;
        foreach( $this->args as $key => $value ) {
            /* skip action column */
            if( $key === 'action' || $key === '__ajax_request' )
                continue;

            $hasError = $this->validateparam( $key );
            if( $hasError )
                $error = true;
        }
        if( $error )
            $this->result->error( _('Validation Error') );
        return $error;
    }

    function recordNotFound()
    {
        return $this->error( __("%1 not found, can not update.", $this->record->getLabel()  ) );
    }


    function successMessage($ret) {
        return __('%1 Record is updated.', $this->record->getLabel() );
    }

    function errorMessage($ret) {
        return __('%1 update failed.', $this->record->getLabel() );
    }

    function updateSuccess($ret) {
        return $this->success($this->successMessage($ret) , array( 'id' => $this->record->id ));
    }

    function updateError($ret) {
        return $this->error($this->errorMessage($ret));
    }
}


