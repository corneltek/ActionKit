<?php
namespace ActionKit\RecordAction;

abstract class CreateRecordAction 
    extends BaseRecordAction
{
    const TYPE = 'create';

    public $enableLoadRecord = false;

    public function create($args)
    {
        $ret = $this->record->create( $args );

        /* error checking */
        if( false === $ret->success ) {
            $this->convertRecordValidation( $ret );
            if( function_exists('fb') ) {
                fb( $ret->message );
                fb( $ret->exception );
                fb( $ret->sql );
                fb( $ret->vars );
            }
            return $this->createError( $ret );
        }
        return $this->createSuccess( $ret );
    }

    /**
     * runValidate inherited from parent class.
     * */
    public function run()
    {
        /* default run method , to run create action */
        $ret = $this->create( $this->args );
        if( $this->nested && ! empty($this->relationships) ) {
            $ret = $this->processSubActions();
        }
        return $ret;
    }

    public function successMessage($ret)
    {
        return __("%1 Record is created." , $this->record->getLabel() );
    }

    public function errorMessage($ret)
    {
        // XXX: should show exception message when error is found.
        if($ret->exception)
            return __('Can not create %1 record: %2' , $this->record->getLabel(), $ret->exception->getMessage() );
        return __('Can not create %1 record.' , $this->record->getLabel() );
    }

    public function createSuccess($ret) 
    {
        return $this->success( $this->successMessage($ret) , array( 
            'id' => $this->record->id
        ));
    }

    public function createError($ret) 
    {
        return $this->error( $this->errorMessage($ret) );
    }

}


