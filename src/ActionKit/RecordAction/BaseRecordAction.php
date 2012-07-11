<?php
namespace ActionKit\RecordAction;
use ActionKit\Action;
use ActionKit\ColumnConvert;
use ActionKit\ActionGenerator;
use ActionKit\Exception\ActionException;

abstract class BaseRecordAction extends Action
{
    const TYPE = 'base';


    /**
     *
     * @var Phifty\Model
     */
    public $record; // record schema object


    /**
     * @var string Record class
     */
    public $recordClass;

    public function __construct( $args = array(), $record = null, $currentUser = null ) 
    {
        // record name is in Camel case
        if( ! $this->recordClass ) {
            throw new ActionException( sprintf('Record class is not specified.' , $this ));
        }
        if( $record && ! is_a($record,'LazyRecord\BaseModel') ) {
            throw new ActionException( 'The record object you specified is not a BaseModel object.' , $this );
        }

        $this->record = $record ?: new $this->recordClass;

        if( ! $record ) {   // for create action, we don't need to create record
            if( $this->getType() !== 'create' ) {
                if( ! $this->loadRecordFromArguments( $args ) ) {
                    throw new ActionException('Record action can not load record', $this );
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
        if( ! $this->record ) {
            throw new ActionException('Record object is empty.', $this );
        }

        foreach( $this->record->getColumns() as $column ) {
            if( ! isset($this->params[$column->name] ) ) {
                $this->params[ $column->name ] = ColumnConvert::toParam( $column , $this->record );
            }
        }
    }



    /**
     * Default base record action schema 
     *
     * Inherits columns from record schema.
     * In this method, we use column converter to 
     * convert record columns into action param objects.
     */
    function schema() 
    {
        $this->useRecordSchema();
    }


    /**
     * Get current action type
     *
     * @return string 'create','update','delete','bulk_delete'
     */
    function getType() 
    {
        return static::TYPE;
    }


    /**
     * Get current record
     */
    function getRecord() 
    {
        return $this->record; 
    }


    /**
     * Set record 
     */
    function setRecord($record)
    {
        $this->record = $record;
    }


    /**
     * Permission check method
     *
     * We should call model's currentUserCan method
     * 
     * @see Phifty\Model
     */
    function currentUserCan( $user )
    {
        return true;
    }


    /**
     * Convert record validation object to action validation 
     * result.
     *
     * @param LazyRecord\OperationResult $ret
     */
    function convertRecordValidation( $ret ) 
    {
        if( $ret->validations ) {
            foreach( $ret->validations as $vld ) {
                $this->result->addValidation( $vld->field , array( 
                    ( $vld->success ? 'valid' : 'invalid' ) => $vld->message 
                )); 
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

        // trigger spl classloader if needed.
        if( class_exists($ret->action_class,true) ) {
            return $ret->action_class;
        }
        eval( $ret->code );
        return $ret->action_class;
    }

}

