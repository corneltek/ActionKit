<?php

namespace Phifty\Action;
use Phifty\Action\RecordAction;

class UpdateRecordAction 
    extends \Phifty\Action\RecordAction
{

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

            var_dump( $ret ); 
            
            $this->convertRecordValidation( $ret );
            return $this->updateError( $ret );
        }

        return $this->updateSuccess($ret);
    }

    function run() 
    { 
        return $this->update( $this->args );
    }

    function recordNotFound()
    {
        return $this->error( __("%1 not found, can not update.", $this->record->getLabel()  ) );
    }

    function updateSuccess($ret)
    {
        return $this->success( __('%1 updated.', $this->record->getLabel() ) , array( 'id' => $this->record->id ) );
    }

    function updateError($ret)
    {
        return $this->error(  __('%1 update failed.') , $this->record->getLabel() );
    }


}


