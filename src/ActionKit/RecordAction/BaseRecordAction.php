<?php
namespace ActionKit\RecordAction;
use ActionKit\Action;
use ActionKit\ColumnConvert;
use ActionKit\ActionGenerator;
use ActionKit\Exception\ActionException;
use ActionKit\CRUD;
use LazyRecord\Schema\SchemaDeclare;
use Exception;

class BaseRecordAction extends Action
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

    public $enableLoadRecord = false;

    public function successMessage($ret) { 
        return $ret->message;
    }

    public function errorMessage($ret) {
        return $ret->message;
    }


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
     *      Action::init
     *      Action::schema
     *    BaseRecordAction::loadRecordValuesToParams
     *
     *
     * @param array                $args
     * @param LazyRecord\BaseModel $record
     */
    public function __construct( $args = array(), $record = null, $currentUser = null )
    {
        // record name is in Camel case
        if ( ! $this->recordClass && $record ) {
            $this->recordClass = get_class($record);
        }

        if ( ! $this->recordClass ) {
            throw new ActionException( sprintf('recordClass is not defined.' , $this ));
        }

        if ( $record && ! is_subclass_of($record,'LazyRecord\\BaseModel',true) ) {
            throw new ActionException( 'The record object you specified is not a BaseModel object.' , $this );
        }

        if ( ! $record ) {
            $record = new $this->recordClass;
        }

        $this->setRecord($record);

        if ( ! $record->id && $this->getType() !== 'create' && $this->enableLoadRecord ) {
            // for create action, we don't need to create record
            if ( ! $this->loadRecordFromArguments( $args ) ) {
                throw new ActionException( get_class($this) . " Record action can not load record from {$this->recordClass}", $this );
            }
        }

        // initialize schema , init base action stuff
        parent::__construct( $args , $currentUser );

        if ( $this->record->id ) {
            // load record values to params
            $this->loadRecordValuesToParams();
        }
    }



    /**
     * This method takes column objects from record schema,
     * and convert them into param objects.
     */
    public function useRecordSchema()
    {
        $this->initRecordColumn();
    }


    /**
     * Load record values into params
     *
     */
    public function loadRecordValuesToParams()
    {
        /* load record value */
        foreach ( $this->record->getColumns(true) as $column ) {
            if ($val = $this->record->{ $column->name }) {
                if ( isset($this->params[ $column->name ]) ) {
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
        if ( isset( $args['id'] )) {
            return $this->record->load( $args['id'] )->success;
        }
        return false;
    }

    /**
     * Convert model columns to action columns
     */
    public function initRecordColumn()
    {
        if (! $this->record) {
            throw new ActionException('Record object is empty.', $this );
        }
        $this->initParamsFromColumns( $this->record->getColumns(true), $this->record );
    }

    public function resetParams() {
        // reset params
        $this->params = array();
    }

    public function initParamsFromColumns($columns, $record = null) {
        foreach ( $columns as $column ) {
            if ( ! isset($this->params[$column->name] ) ) {
                // do not render this field if renderable === false
                if ( false !== $column->get('renderable') ) {
                    $this->params[ $column->name ] = ColumnConvert::toParam( $column , $record );
                }
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

        // build relationship config from model schema
        $relations = $this->record->getSchema()->relations;
        foreach ( $relations as $rId => $r ) {
            $this->addRelation($rId, $r);
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



    public function hasRelation($relationId)
    {
        return isset( $this->relationships[$relationId] );
    }


    public function getRelation($relationId)
    {
        if ( isset($this->relationships[$relationId]) ) {
            return $this->relationships[$relationId];
        }
    }



    /**
     * Add relationship config
     *
     *  $this->addRelation('images',array(
     *      'has_many' => true,
     *      'record' => 'Product\\Model\\ProductImage',
     *      'self_key' => 'product_id',
     *      'foreign_key' => 'id',
     *  );
     *
     * @param string $relationId
     * @param array $config relationship config
     */
    public function addRelation($relationId,$config) 
    {
        $this->relationships[ $relationId ] = $config;
    }


    /**
     *
     * array(
     *    'many_to_many'    => true,
     *
     *    // required from editor
     *    'collection'      => 'Product\\Model\\CategoryCollection',
     *
     *    // for inter relationship processing
     *    'from'            => 'product_categories',
     *    'inter_foreign_key' => 'category_id',
     *    'filter' => function($collection, $record) {
     *        return $collection;
     *    }
     * )
     */
    /*
    public function addManyToManyRelation($relationId,$config)
    {
        $this->relationships[ $relationId ] = array_merge(array( 
            'many_to_many' => true,
        ), $config);
    }

    public function addHasManyRelation($relationId,$config)
    {
        $requiredKeys = array('self_key','record','foreign_key');
        foreach( $requiredKeys as $k) {
            if ( ! isset($config[$k]) ) {
                throw new Exception("key $k is required for has-many relationship");
            }
        }
        $this->relationships[ $relationId ] = array_merge(array( 
            'has_many' => true,
        ), $config);
    }
    */

    public function removeRelation($id)
    {
        unset($this->relationships[$id]);
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
        return CRUD::generate($recordClass, $type );
    }



    /**
     * Base on the relationship definition, we should
     * create the action object to process the nested data.
     */
    public function createSubAction($relation,$args)
    {
        $record = null;
        if ( isset($relation['foreign_schema']) ) {
            $schema = new $relation['foreign_schema'];
            $recordClass = $schema->getModelClass();
            // create record object, and load it with primary id
            $record = new $recordClass;
            if ( isset($args['id']) && $args['id'] ) {
                $record->load( $args['id'] );
            }
        }

        // for relationships that has defined an action class,
        // we should just use it.
        if ( isset($relation['action']) ) {
            $class = $relation['action'];

            // for record-based action, we should pass the record object.
            if ( is_subclass_of($class,'ActionKit\\RecordAction\\BaseRecordAction',true) ) {
                return new $class($args, $record);
            } else {
                // for simple action class without record.
                return $class($args);
            }

        } else {
            // If the record is loaded
            if ($record->id) {

                // if the update_action field is defined,
                // then we should use the customized class to process our data.
                if ( isset($relation['update_action']) ) {
                    $class = $relation['update_action'];
                    return new $class($args,$record);
                }
                return $record->asUpdateAction($args);

            } else {

                // we are going to create related records with subactions
                // just ensure that we've unset the record identity.
                unset($args['id']);
                if ( isset($relation['create_action']) ) {
                    $class = $relation['create_action'];
                    return new $class($args,$record);
                }
                return $record->asCreateAction($args);

            }
            // won't be here.
        }
        // won't be here.
    }


    public function fetchOneToManyRelationCollection($relationId) {
        $record = $this->record;
        if ( $record->id && isset($record->{ $relationId }) ) {
            return $record->{$relationId};
        }
    }

    public function fetchManyToManyRelationCollection($relationId) {
        $relation = $this->getRelation($relationId);
        return $relation->newForeignForeignCollection(
            $this->record->getSchema()->getRelation($relation['relation_junction'])
        );
    }


    public function processSubActions()
    {
        foreach ($this->relationships as $relationId => $relation) {

            $argsList = $this->arg( $relationId );
            if ( ! $argsList) {
                continue;
            }

            if ( SchemaDeclare::has_many === $relation['type'] ) {
                // XXX: use the lazyrecord schema relationship!!!
                //
                //
                // In this behavior, we don't handle the 
                // previous created records, only the records from the form submit.
                //
                // for each records, we get the action field, and create the subaction
                // to process the "one-many" relationship records.
                //
                // the subactions are not only for records, it may handle
                // pure action objects.
                $foreignKey  = $relation['foreign_column'];
                $selfKey     = $relation['self_column'];


                // the argument here we expect from form post is:
                //
                //     $args[relationId][index][field_name] => value
                // 
                // the input name is layouted like this:
                //
                //     <input name="images[1][image]" value="..."
                //     <input name="images[1][title]" value="..."
                //
                // where the index is 'the relational record id' or the timestamp.
                //
                // so let us iterating these fields
                foreach ($argsList as $index => $args) {
                    // before here, we've loaded/created the record,
                    // so that we already have a record id.

                    // we should update related records with the main record id
                    // by using self_key and foreign_key
                    $args[$foreignKey] = $this->record->{$selfKey};

                    // get file arguments from fixed $_FILES array.
                    // the ->files array is fixed in Action::__construct method

                    if ( isset($this->files[ $relationId ][ $index ]) ) {
                        $args['_FILES'] = $this->files[ $relationId ][ $index ];
                    } else {
                        $args['_FILES'] = array();
                    }
                    $action = $this->createSubAction($relation, $args);

                    if ( $action->invoke() === false ) {
                        // transfrer the error result to self,
                        // then report error.
                        $this->result = $action->result;
                        return false;
                    }
                }
            } elseif ( SchemaDeclare::many_to_many === $relation['type']) {
                // Process the junction of many-to-many relationship
                //
                // For the many-to-many relationship, we should simply focus on the
                // junction records. so that we are not going to render these "end-records" as form.
                //
                // But we need to render these "end-records" as the name of options.
                //
                // Here we handle somethine like:
                //
                //
                //      categories[index][id] = category_id
                //      categories[index][_connect] = 1 || 0    (should we connect ?)
                //      
                $record = $this->record;
                $middleRelation    = $record->getSchema()->getRelation($relation['relation_junction']);
                $middleSchema      = new $middleRelation['foreign_schema'];
                $middleRecordClass = $middleSchema->getModelClass();
                $middleRecord      = new $middleRecordClass;

                $foreignRelation = $middleRecord->getSchema()->getRelation( $relation['relation_foreign'] ); // which should be 'belongsTo' relation
                $foreignSchema   = new $foreignRelation['foreign_schema'];

                $collectionClass = $foreignSchema->getCollectionClass();
                $collection      = new $collectionClass;

                $connected = array();

                $from = $relation['relation_junction'];
                $middleForeignKey = $foreignRelation['self_column'];

                $junctionRecords = $record->{$from};

                // get existing records
                $foreignRecords = $record->{$relationId};
                foreach ( $foreignRecords as $fRecord ) {
                    $connected[ $fRecord->id ] = $fRecord;
                }

                foreach ($argsList as $index => $args) {
                    // foreign record primary key
                    $fId  = $args['_foreign_id'];

                    // find junction record or create a new junction record
                    // create the junction record if it is not connected.
                    if ( isset($args['_connect']) && $args['_connect'] ) {
                        $argsCreate = array_merge( $args , array( $middleForeignKey => $fId ));
                        unset($argsCreate['_connect']);
                        unset($argsCreate['_foreign_id']);

                        if ( ! isset($connected[ $fId ]) ) {
                            $newRecord = $junctionRecords->create($argsCreate);
                            // $ret = $newRecord->popResult();
                            // $ret->throwExceptionIfFailed();
                        } else {
                            // update the existing record data.
                            foreach( $junctionRecords as $r ) {
                                if ( $r->{ $middleForeignKey } == $fId ) {
                                    $ret = $r->update($argsCreate);
                                    $ret->throwExceptionIfFailed();
                                }
                            }
                        }


                    } else {
                        // not connected, but if the connection exists, we should delete the connection here.
                        if ( isset($connected[ $fId ]) ) {
                            $jrs = clone $junctionRecords;
                            $jrs->where(array( $middleForeignKey => $fId ));
                            // delete the connection
                            $jrs->first()->delete();
                            unset($connected[ $fId ]);
                        }
                    }
                }
            }
        }
        return true;
    }

}
