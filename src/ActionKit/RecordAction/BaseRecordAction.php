<?php
namespace ActionKit\RecordAction;
use Exception;
use ActionKit\Action;
use ActionKit\ColumnConvert;
use ActionKit\ActionGenerator;

abstract class BaseRecordAction extends Action
{
    const TYPE = 'base';

    public $record; // record schema object

    public $recordClass;

    public function __construct( $args = array(), $record = null, $currentUser = null ) 
    {
        // record name is in Camel case
        if( ! $this->recordClass ) {
            throw new Exception( sprintf('Record class of "%s" is not specified.' , get_class($this) ));
        }
        if( $record && ! is_a($record,'LazyRecord\BaseModel') ) {
            throw new Exception( 'The record object you specified is not a BaseModel object.' );
        }

        $this->record = $record ?: new $this->recordClass;

        if( ! $record ) {   // for create action, we don't need to create record
            if( $this->getType() !== 'create' ) {
                if( ! $this->loadRecordFromArguments( $args ) ) {
                    throw new Exception('Record action can not load record');
                }
            }
        }

        // Convert id column object from record schema to
        // Action param object.
        if( $column = $this->record->getColumn('id') ) {
            if( ! isset($this->params[$column->name] ) ) {
                $this->params[ $column->name ] = ColumnConvert::toParam( $column , $this->record );
            }
        }

        /* run schema , init base action stuff */
        parent::__construct( $args , $currentUser );
    }


    /**
     * This method takes column objects from record schema,
     * and convert them into param objects.
     */
    protected function useRecordSchema()
    {
        $this->initRecordColumn();
    }


    /**
     * Load record from arguments (by primary key: id)
     *
     * @return boolean
     */
    public function loadRecordFromArguments($args) 
    {
        if( isset( $args['id'] )) {
            return $this->record->load( $args['id'] )->success;
        }
        return false;
    }

    /**
     * Convert model columns to action columns 
     */
    function initRecordColumn()
    {
        if( ! $this->record )
            return;
        foreach( $this->record->getColumns() as $column ) {
            if( ! isset($this->params[$column->name] ) ) {
                $this->params[ $column->name ] = ColumnConvert::toParam( $column , $this->record );
            }
        }
    }


    public function schema() 
    {
        $this->useRecordSchema();
    }

    function getType() 
    {
        return static::TYPE;
    }

    public function getRecord() 
    {
        return $this->record; 
    }

    public function setRecord($record)
    {
        $this->record = $record;
    }

    public function currentUserCan( $user )
    {
        return true;
    }


    /**
     * Convert record validation object to action validation result.
     *
     * @param LazyRecord\OperationResult $ret
     */
    function convertRecordValidation( $ret ) 
    {
        if( $ret->validations ) {
            foreach( $ret->validations as $vld ) {
                if( $vld->success ) {
                    $this->result->addValidation( $vld->field , array( "valid" => $vld->message )); 
                } else {
                    $this->result->addValidation( $vld->field , array( "invalid" => $vld->message ));
                }
            }
        }
    }



    /**
     * Create CRUD class
     *
     * @param string $recordClass
     * @param string $type
     *
     * @return string class code
     */
    static function createCRUDClass( $recordClass , $type ) 
    {
        $gen = new ActionGenerator(array( 'cache' => true ));
        $ret = $gen->generateClassCode( $recordClass , $type );

        if( class_exists($ret->action_class,true) ) {
            return $ret->action_class;
        }
        eval( $ret->code );
        return $ret->action_class;
    }

}

