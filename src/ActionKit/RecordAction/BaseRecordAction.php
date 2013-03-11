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

    abstract public function successMessage($ret);

    abstract public function errorMessage($ret);

    /**
     * Construct an action object.
     *
     *    $action = new UpdateProductAction(array( ... ), new Product, $currentUser);
     *
     *
     * Here we override the default __construct from Action class.
     *
     * The initialize flow here is:
     *
     *    BaseRecordAction::__construct
     *    BaseRecordAction::setRecord
     *      Action::__construct
     *      Action::schema
     *      Action::init
     *    BaseRecordAction::loadRecordValues
     *
     *
     * @param array                $args
     * @param LazyRecord\BaseModel $record
     */
    public function __construct( $args = array(), $record = null, $currentUser = null )
    {
        // record name is in Camel case
        if ( ! $this->recordClass ) {
            throw new ActionException( sprintf('Record class is not specified.' , $this ));
        }

        if ( $record && ! is_subclass_of($record,'LazyRecord\\BaseModel',true) ) {
            throw new ActionException( 'The record object you specified is not a BaseModel object.' , $this );
        }

        if ( ! $record ) {
            $record = new $this->recordClass;
        }

        $this->setRecord($record);

        if (! $record->id) {   // for create action, we don't need to create record
            if ( $this->getType() !== 'create' && $this->enableLoadRecord ) {
                if ( ! $this->loadRecordFromArguments( $args ) )
                    throw new ActionException('Record action can not load record', $this );
            }
        }

        // initialize schema , init base action stuff
        parent::__construct( $args , $currentUser );

        if ( $this->record->id ) {
            $this->loadRecordValues();
        }
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
     *
     */
    public function loadRecordValues()
    {
        /* load record value */
        foreach ( $this->record->getColumns(true) as $column ) {
            if ($val = $this->record->{ $column->name }) {
                if ( isset($this->params[ $column->name ]) )
                    $this->params[ $column->name ]->value = $val;
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
        if ( isset( $args['id'] )) {
            return $this->record->load( $args['id'] )->success;
        }

        return false;
    }

    /**
     * Convert model columns to action columns
     */
    protected function initRecordColumn()
    {
        if (! $this->record) {
            throw new ActionException('Record object is empty.', $this );
        }
        foreach ( $this->record->getColumns(true) as $column ) {
            if ( ! isset($this->params[$column->name] ) ) {
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

        // Convert id column object from record schema to
        // Action param object.
        if ( $column = $this->record->getColumn('id') ) {
            if ( ! isset($this->params[$column->name] ) ) {
                $this->params[ $column->name ] = ColumnConvert::toParam( $column , $record );
            }
        }
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
        if ($ret->validations) {
            foreach ($ret->validations as $vld) {
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
    public static function createCRUDClass( $recordClass , $type )
    {
        $gen = new ActionGenerator(array( 'cache' => true ));
        $ret = $gen->generateClassCode( $recordClass , $type );

        // trigger spl classloader if needed.
        if ( class_exists($ret->action_class,true) ) {
            return $ret->action_class;
        }
        eval( $ret->code );

        return $ret->action_class;
    }

    public function createRelationAction($relation,$args)
    {
        $record = null;
        if ( isset($relation['record']) ) {
            $recordClass = $relation['record'];
            // create record object, and load it with primary id
            $record = new $recordClass;
            if ( isset($args['id']) && $args['id'] ) {
                $record->load( $args['id'] );
            }
        }

        if ( isset($relation['action']) ) {
            $class = $relation['action'];

            // which is a record-based action.
            if ( is_subclass_of($class,'ActionKit\\RecordAction\\BaseRecordAction',true) ) {
                return new $class($args, $record);
            }

            // which is a simple action
            return $class($args);
        } else {
            // run subaction
            if ($record->id) {
                if ( isset($relation['update_action']) ) {
                    $class = $relation['update_action'];

                    return new $class($args,$record);
                }

                return $record->asUpdateAction($args);
            }

            unset($args['id']);
            if ( isset($relation['create_action']) ) {
                $class = $relation['create_action'];

                return new $class($args,$record);
            }

            return $record->asCreateAction($args);
        }
    }

    public function processSubActions()
    {
        foreach ($this->relationships as $relationId => $relation) {
            if ( ! isset($relation['has_many']) )
                continue;

            $recordClass = $relation['record'];
            $foreignKey = $relation['foreign_key'];
            $selfKey = $relation['self_key'];
            $argsList = $this->arg( $relationId );

            if (!$argsList)
                continue;

            foreach ($argsList as $index => $args) {
                // update related records with the main record id
                // by using self_key and foreign_key
                $args[$selfKey] = $this->record->{$foreignKey};

                // get file arguments from fixed $_FILES array.
                // the ->files array is fixed in Action::__construct method
                $files = array();
                if ( isset($this->files[ $relationId ][ $index ]) ) {
                    $files = $this->files[$relationId][ $index ];
                }

                $action = $this->createRelationAction($relation,$args);
                $action->files = $files;
                if ( $action->invoke() === false ) {
                    $this->result = $action->result;

                    return false;
                }
            }
        }

        return true;
    }

}
