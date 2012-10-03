<?php
namespace ActionKit\RecordAction;
use ActionKit\Action;
use ActionKit\ColumnConvert;
use ActionKit\ActionGenerator;
use ActionKit\Exception\ActionException;

abstract class BaseRecordAction extends Action
{
    const TYPE = 'base';


    public $nested = false;
    public $relationships = array();

    /**
     *
     * @var Phifty\Model
     */
    public $record; // record schema object


    /**
     * @var string Record class
     */
    public $recordClass;

    public $enableLoadRecord = true;

    abstract function successMessage($ret);

    abstract function errorMessage($ret);

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
            if( $this->getType() !== 'create' && $this->enableLoadRecord ) {
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
        $this->loadRecordValues();
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
     * Load record values into params
     */
    public function loadRecordValues() {
        /* load record value */
        if( $this->record->id ) {
            foreach( $this->record->getColumns(true) as $column ) {
                if( $val = $this->record->{ $column->name } ) {
                    if( isset($this->params[ $column->name ]) )
                        $this->params[ $column->name ]->value = $val;
                }
            }
        }
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
    protected function initRecordColumn()
    {
        if( ! $this->record ) {
            throw new ActionException('Record object is empty.', $this );
        }
        foreach( $this->record->getColumns(true) as $column ) {
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
    public function schema() 
    {
        $this->useRecordSchema();
    }


    /**
     * Get current action type
     *
     * @return string 'create','update','delete','bulk_delete'
     */
    public function getType() 
    {
        return static::TYPE;
    }


    /**
     * Get current record
     */
    public function getRecord() 
    {
        return $this->record; 
    }


    /**
     * Set record 
     *
     * @param Phifty\Model $record
     */
    public function setRecord($record)
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
    public function currentUserCan( $user )
    {
        return true;
    }


    /**
     * Convert record validation object to action validation 
     * result.
     *
     * @param LazyRecord\OperationResult $ret
     */
    public function convertRecordValidation( $ret ) 
    {
        if( $ret->validations ) {
            foreach( $ret->validations as $vld ) {
                $this->result->addValidation( $vld->field , array( 
                    'valid'   => $vld->valid,
                    'message' => $vld->message,
                    'field'   => $vld->field,
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



    public function processSubActions()
    {
        foreach( $this->relationships as $relationId => $relation ) {
            $recordClass = $relation['record'];
            $foreignKey = $relation['foreign_key'];
            $selfKey = $relation['self_key'];
            $argsList = $this->arg( $relationId );

            if(!$argsList)
                continue;

            foreach( $argsList as $index => $args ) {
                // update related records with the main record id 
                // by using self_key and foreign_key
                $args[$selfKey] = $this->record->{$foreignKey};
                $files = array();
                if( isset($this->files[ $relationId ][ $index ]) ) {
                    $files = $this->files[$relationId][ $index ];
                }

                // run subaction
                $record = new $recordClass;
                if( isset($args['id']) && $args['id'] ) {
                    $record->load( $args['id'] );
                    $action = $record->asUpdateAction($args);
                } else {
                    unset($args['id']);
                    $action = $record->asCreateAction($args);
                }
                $action->files = $files;
                if( $action->invoke() === false ) {
                    $this->result = $action->result;
                    return false;
                }
            }
        }
        return true;
    }

}

