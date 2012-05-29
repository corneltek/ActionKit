<?php
namespace ActionKit\RecordAction;
use Exception;
use ActionKit\Action;

/*
    use ActionKit\RecordAction;


    # returns CreateRecordAction
    $createAction = BaseRecordAction::generate( 'RecordName' , 'Create' );

    # returns UpdateRecordAction
    $updateAction = BaseRecordAction::generate( 'RecordName' , 'Update' );


    XXX: validation should be built-in in Model

*/
abstract class BaseRecordAction extends Action
{
    public $record; // record schema object
    public $recordClass;
    public $type;  // action type (create,update,delete...)

    public function __construct( $args = array(), $record = null, $currentUser = null ) 
    {
        /* run schema , init base action stuff */
        parent::__construct( $args , $currentUser );
        if( ! $this->recordClass ) {
            throw new Exception( sprintf('Record class of "%s" is not specified.' , get_class($this) ));
        }

        // record name is in Camel case
        $class = $this->recordClass;
        $this->record = $record ? $record : new $class;

        if( is_a( $this , 'ActionKit\RecordAction\CreateRecordAction' ) ) {
            $this->type = 'create';
        }
        elseif( is_a( $this, 'ActionKit\RecordAction\UpdateRecordAction' ) ) {
            $this->type = 'update';
        }
        elseif( is_a( $this, 'ActionKit\RecordAction\DeleteRecordAction' ) ) {
            $this->type = 'delete';
        } else {
            throw new Exception( sprintf('Unknown Record Action Type: %s' , get_class($this) ));
        }

        $this->initRecord();
        $this->initRecordColumn();
    }


    /**
     * load record
     */
    function initRecord() 
    {
        if( isset( $this->args['id'] ) && ! $this->record->id ) {
            $this->record->load( $this->args['id'] );
        }
    }

    /**
     * Convert model columns to action columns 
     */
    function initRecordColumn()
    {
        if( $this->record ) {
            foreach( $this->record->getColumns() as $column ) {
                if( ! isset($this->params[$column->name] ) ) {
                    $this->params[ $column->name ] = \ActionKit\ColumnConvert::toParam( $column , $this->record );
                }
            }
        }
    }

    // the schema of record action is from record class.
    function schema() 
    {
        /*
        $this->record->actionSchema( $this , $this->getType() );
        */
    }

    function getType() 
    {
        return $this->type;
    }

    function getRecord() 
    {
        return $this->record; 
    }

    function setRecord($record)
    {
        $this->record = $record;
    }

    function currentUserCan( $user )
    {
        return true;
    }

    function convertRecordValidation( $ret ) {
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
     * TODO: seperate this to CRUD actions 
     */
    function runUpdateValidate()
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


    /* just run throgh all params */
    function runCreateValidate()
    {
        return parent::runValidate();
    }

    function runDeleteValidate()
    {
        if( isset( $this->args['id'] ) )
            return false;
        return true;
    }

    function runValidate()
    {
        if( $this->type == 'delete' )
            return $this->runDeleteValidate();
        elseif( $this->type == 'update' )
            return $this->runUpdateValidate();
        elseif( $this->type == 'create' )
            return $this->runCreateValidate();
        else
            return parent::runValidate();
    }


    /**
     * Create Record Action dynamically.
     *
     * RecordAction::generate( 'PluginName' , 'News' , 'Create' );
     * will generate:
     * PluginName\Action\CreateNews
     */
    static function generate( $ns , $modelName , $type )
    {
        $modelClass  = '\\' . $ns . '\Model\\' . $modelName;
        $actionName  = $type . $modelName;
        $baseAction  = '\ActionKit\\RecordAction\\' . $type . 'RecordAction';
        $code =<<<CODE
namespace $ns\\Action {
    class $actionName extends $baseAction
    {
        public \$recordClass = '$modelClass';
    }
}
CODE;
        return $code;
    }

    static function createCRUDClass( $recordClass , $type ) 
    {
        /* split class to ns, model name */
        if( preg_match( '/(\w+)\\\Model\\\(\w+)/' , $recordClass , $regs ) ) 
        {
            list( $orig, $ns , $modelName ) = $regs;
            $class = '\\' . $ns . '\Action\\' . $type . $modelName;
            if( class_exists( $class, true) )
                return $class;

            $code = self::generate( $ns , $modelName , $type );
            eval( $code );
            return $class;
        }
    }

}

?>
