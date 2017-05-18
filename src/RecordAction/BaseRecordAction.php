<?php
namespace ActionKit\RecordAction;
use ActionKit\Action;
use ActionKit\ColumnConvert;
use ActionKit\Exception\ActionException;
use ActionKit\Exception\RequiredConfigKeyException;
use ActionKit\ActionTemplate\RecordActionTemplate;
use ActionKit\RecordAction\CreateRecordAction;
use Maghead\Schema\DeclareSchema;
use Maghead\Schema\Relationship\Relationship;
use Maghead\Runtime\Model;
use Maghead\Runtime\Result;
use Exception;

class BaseRecordAction extends Action
{
    const TYPE = 'base';

    /**
     *
     * @var Phifty\Model
     */
    protected $record; // record schema object

    protected $schema;

    /**
     * @var string Record class
     */
    public $recordClass;

    public $enableLoadRecord = false;

    /**
     * The operation result of CRUD
     *
     * @var Maghead\Runtime\Result
     */
    public $recordResult;


    /**
     * Construct an action object.
     *
     *    $action = new UpdateProductAction(array( ... ), new Product, $options);
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
     * @param Maghead\Runtime\Model $record
     */
    public function __construct(array $args = array(), $options = array())
    {
        $record = null;

        if ($options instanceof Model) {
            $record = $options;
            $options = array(); // reassign $options as array
        } else if (is_array($options) && isset($options['record'])) {
            $record = $options['record'];
        }

        if (isset($options['record_class'])) {
            $this->recordClass = $options['record_class'];
        } else if (! $this->recordClass && $record) {
            $this->recordClass = get_class($record);
        }

        if (! $this->recordClass ) {
            throw new ActionException('recordClass is not defined.', $this);
        }

        if ($record === null) {
            $record = new $this->recordClass;
        }

        $this->schema = $this->recordClass::getSchema();
        $this->setRecord($record);

        // copy relationship config from model schema for extending the properties later.
        if ($relations = $this->schema->relations) {
            foreach ($relations as $rId => $relation) {
                $this->addRelation($rId, $relation);
            }
        }

        // CreateRecordAction doesn't require primary key to be existed.
        if (! $record->id && ! $this instanceof CreateRecordAction && $this->enableLoadRecord ) {
            // for create action, we don't need to create record
            $record = $this->loadRecordFromArguments($args);
            if (!$record) {
                throw new ActionException(get_class($this). " Record action can not load record from {$this->recordClass}", $this);
            }
            $this->setRecord($record);
        }

        // initialize schema , init base action stuff
        parent::__construct($args , $options);

    }

    /**
     * Load record values into param instead of arguments.
     *
     * @override
     */
    protected function loadParamValues()
    {
        if (!$this->record) {
            return;
        }

        // load record values into param objects
        if ($columns = $this->record->getColumns(true)) {
            foreach ($columns as $column) {
                $name = $column->name;
                if (isset($this->params[$name] ) ) {
                    $param = $this->params[$name];
                    $value = $this->record->getValue($name);
                    if ($value !== NULL) {
                        $param->value($value);
                    } else {
                        $param->value($column->getDefaultValue());
                    }
                }
            }
        }
        parent::loadParamValues();
    }


    /**
     * This method takes column objects from record schema,
     * and convert them into param objects.
     */
    public function useRecordSchema()
    {
        $this->initParamsFromColumns($this->schema->getColumns(true), $this->record);
    }


    /**
     * Load record values into params
     *
     */
    protected function loadRecordValuesToParams()
    {
        foreach ($this->record->getColumns(true) as $column) {
            if (!isset($this->params[$column->name]) ) {
                continue;
            }
            if ($val = $this->record->{ $column->name }) {
                $this->params[ $column->name ]->value($val);
            }
        }
    }

    /**
     * Load record from arguments (by primary key: id)
     *
     * @return boolean
     */
    protected function loadRecordFromArguments(array $args)
    {
        $primaryKey = $this->schema->primaryKey;
        if (!$primaryKey) {
            return false;
        }
        if (!isset($args[$primaryKey])) {
            throw new ActionException("primary key '{$primaryKey}' missing in the arguments: " . var_export($args, true) );
        }
        return $this->recordClass::findByPrimaryKey($args[$primaryKey]);
    }

    public function resetParams()
    {
        $this->params = [];
    }

    /**
     * Creates param objects from schema columns
     */
    public function initParamsFromColumns(array $columns, Model $record = null)
    {
        foreach ($columns as $column) {
            if (isset($this->params[$column->name] )) {
                continue;
            }
            // do not render this field if renderable === false
            if (false === $column->get('renderable')) {
                continue;
            }
            $this->params[ $column->name ] = ColumnConvert::toParam($column , $record, $this);
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
    public function setRecord(Model $record)
    {
        $this->record = $record;

        // Create primary key column from the record schema with the current record value.
        if (!$this->schema->primaryKey) {
            return;
        }
        if ($column = $this->schema->getColumn($this->schema->primaryKey)) {
            $this->params[$column->name] = ColumnConvert::toParam($column , $record, $this);
        }
    }



    /**
     * Permission check method
     *
     * We should call model's currentUserCan method
     *
     * @see Phifty\Model
     */
    public function currentUserCan( $user, $right, $args = array() )
    {
        return true;
    }

    /**
     * Convert record validation object to action validation
     * result.
     *
     * @param Maghead\Result $ret
     */
    public function convertRecordValidation(Result $ret)
    {
        if ($ret->validations) {
            foreach ($ret->validations as $vld) {
                $this->result->addValidation($vld['field'] , array(
                    'valid'   => $vld['valid'],
                    'message' => $vld['message'],
                    'field'   => $vld['field'],
                ));
            }
        }
    }


    public function hasRelation($relationId)
    {
        return isset( $this->relationships[$relationId] );
    }

    /**
     * @return Maghead\Schema\Relationship relationship object
     */
    public function getRelation($relationId)
    {
        if (isset($this->relationships[$relationId]) ) {
            return $this->relationships[$relationId];
        }
    }



    /**
     * Add relationship config
     *
     *  $this->addRelation('images', array(
     *      'has_many' => true,
     *      'record' => 'Product\\Model\\ProductImage',
     *      'self_key' => 'product_id',
     *      'foreign_key' => 'id',
     *  );
     *
     * @param string $relationId
     * @param array $config relationship config
     */
    public function addRelation($relationId, Relationship $config)
    {
        $this->relationships[ $relationId ] = $config;
    }

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
        list($modelNs, $modelName) = explode('\\Model\\', $recordClass);
        $modelNs = ltrim($modelNs,'\\');
        $actionFullClass = "{$modelNs}\\Action\\{$type}{$modelName}";
        $recordClass  = "{$modelNs}\\Model\\{$modelName}";
        $baseAction   = "\\ActionKit\\RecordAction\\{$type}RecordAction";

        $template = new RecordActionTemplate;
        $generatedAction = $template->generate($actionFullClass, [
            'extends' => $baseAction,
            'properties' => [
                'recordClass' => $recordClass,
            ],
        ]);
        if (!class_exists($actionFullClass ,true)) {
            $generatedAction->load();
        }

        return $actionFullClass;
    }

    public function createSubAction($relation, array $args, array $files = null)
    {
        if (!$relation instanceof Relationship) {
            if (is_string($relation)) {
                $relation = $this->getRelation($relation);
            }
        }
        if (!$relation) {
            throw new Exception("Relationship undefined.");
        }
        return $this->createSubActionWithRelationship($relation, $args, $files);
    }

    /**
     * Base on the relationship definition, this method creates the action
     * object to process the nested data.
     *
     * When creating subaction with relationship, we don't pass the current request object, we
     * only pass the sub-parameters of the relationship (including the sub-section of the $_FILES array)
     *
     * The subaction will create its own ActionRequest object to maintain the
     * sub-parameters.
     *
     * @param Maghead\Schema\Relationship $relation
     * @param array $args
     * @param array $files
     * @return ActionKit\Action
     */
    public function createSubActionWithRelationship(Relationship $relation, array $args, array $files = null)
    {
        $subrecord = null;
        if (!isset($relation['foreign_schema']) ) {
            throw new Exception("Missing relationship foreign_schema");
        }

        $schema = new $relation['foreign_schema'];
        $recordClass = $schema->getModelClass();
        // create record object, and load it with primary id
        $primaryKey = $schema->primaryKey;
        if (isset($args[$primaryKey]) && $args[$primaryKey] ) {
            $subrecord = $recordClass::load( $args[$primaryKey] );
        } else {
            $subrecord = new $recordClass;
        }

        $actionOptions = [
            'parent' => $this,
            'record' => $subrecord,
            'files' => $files,
        ];

        // for relationships that has defined a custom action class,
        // we should just use it.
        if (isset($relation['action'])) {

            $class = $relation['action'];
            return new $class($args, $actionOptions);

        } else if ($subrecord && $subrecord->id) {

            // if the update_action field is defined,
            // then we should use the customized class to process our data.
            if (isset($relation['update_action']) ) {
                $class = $relation['update_action'];
                return new $class($args,$actionOptions);
            }
            return $subrecord->asUpdateAction($args, $actionOptions);

        } else {

            // we are going to create related records with subactions
            // just ensure that we've unset the record identity.
            unset($args[$primaryKey]);
            if (isset($relation['create_action'])) {
                $class = $relation['create_action'];
                return new $class($args,$actionOptions);
            }
            return $subrecord->asCreateAction($args, $actionOptions);
        }
    }

    public function fetchOneToManyRelationCollection($relationId) 
    {
        $record = $this->record;
        if ( $record->id && isset($record->{ $relationId }) ) {
            return $record->{$relationId};
        }
    }

    public function fetchManyToManyRelationCollection($relationId) 
    {
        $relation = $this->getRelation($relationId);
        return $relation->newForeignForeignCollection(
            $this->record->getSchema()->getRelation($relation['relation_junction'])
        );
    }


    public function processSubActions()
    {
        // TODO: Find the type of $relation
        foreach ($this->relationships as $relationId => $relation) {

            // Fetch the arguments from:
            //
            //     name="relationship[index][...]"
            //
            // Hence the structure of $argsList is:
            //
            //     [
            //       {index} => { field1 => ... , field2 => ... },
            //       {index} => { field1 => ... , field2 => ... },
            //       {index} => { field1 => ... , field2 => ... },
            //     ]
            //
            // The {index} here is only used for identify the argument duplication.
            //
            $argsList = $this->arg($relationId) ?: array();

            // Form field '$relationId' for relationship is not an array,
            // that might be scalar field value to be passed to model.
            if (!is_array($argsList)) {
                // TODO: improve me!
                // throw new Exception("Form field '$relationId' for relationship is not an array, got " . var_export($argsList, true) );
                continue;
            }

            $allfiles = $this->request->getFiles();
            $indexList = array_unique(array_merge(array_keys($argsList),  array_keys($allfiles)));
            if (empty($indexList)) {
                continue;
            }

            if (Relationship::HAS_MANY === $relation['type']) {
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
                foreach ($indexList as $index) {

                    if (!isset($argsList[$index])) {
                        continue;
                    }

                    $args = $argsList[$index];

                    // copy csrf token recursively
                    if (isset($this->args['__csrf_token'])) {
                        $args['__csrf_token'] = $this->args['__csrf_token'];
                    }


                    // before here, we've loaded/created the main record,
                    // so that we already have the id of the main record.

                    // we should update the related records with the main record id
                    // by using self_key and foreign_key
                    $args[$foreignKey] = $this->record->{$selfKey};

                    // Get the file arguments from fixed $_FILES array.
                    // the ->files array is fixed in Action::__construct method

                    $files = array();
                    if (isset($allfiles[$relationId][$index])) {
                        $files = $allfiles[$relationId][$index];
                    }

                    $action = $this->createSubActionWithRelationship($relation, $args, $files);
                    if ($action->invoke() === false) {
                        // transfrer the error result to self,
                        // then report error.
                        $this->result = $action->result;
                        return false;
                    }
                }
            } else if (Relationship::MANY_TO_MANY === $relation['type']) {
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





    /**
     * This method provides the default error message of a record action.
     *
     * @codeCoverageIgnore
     */
    public function successMessage($ret) { 
        return $ret->message;
    }

    /**
     * This method provides the default error message of a record action.
     *
     * @codeCoverageIgnore
     */
    public function errorMessage($ret) {
        return $ret->message;
    }


}
