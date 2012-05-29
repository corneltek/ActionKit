<?php
namespace ActionKit\RecordAction;

abstract class UpdateRecordAction 
    extends BaseRecordAction
{
    public $type = 'update';

    function update( $args )
    {
        $id = (int) $args['id'];
        if( ! $id )
            return $this->error( _('Arguments Error') );

        $record = $this->record;
        $record->load( $id );
        if( ! $record->id )
            return $this->recordNotFound();

        $ret = $record->update( $args );
        if( false === $ret->success ) {
            $this->convertRecordValidation( $ret );
            return $this->updateError( $ret );
        }

        return $this->updateSuccess($ret);
    }

    function run() 
    { 
        return $this->update( $this->args );
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


    function successMessage($ret) 
    {
        return __('%1 updated.', $this->record->getLabel() );
    }

    function errorMessage($ret) 
    {
        return __('%1 update failed.', $this->record->getLabel() );
    }

    function updateSuccess($ret)
    {
        return $this->success($this->successMessage($ret) , array( 'id' => $this->record->id ));
    }

    function updateError($ret)
    {
        return $this->error($this->errorMessage($ret));
    }


}


