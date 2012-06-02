<?php
namespace ActionKit\Column;
use ActionKit\Column;
use Phifty\UploadFile;
use Exception;


/**
 * Preprocess image data fields
 *
 * You can easily describe process and options for uploaded files
 *
 *    $this->param('file')
 *          ->putIn('path/to/pool')
 *          ->validExtension( array('png') )
 *          ->renameFile(function() {  })
 */
class File extends Column
{
    public $type = 'file';

    public $validExtensions;
    public $putIn;
    public $renameFile;
    public $sizeLimit;
    public $sourceField;  /* If field is not defined, use this source field */

    public function build()
    {
        // XXX: use CascadingAttribute class setter instead.
        $this->supportedAttributes['validExtensions'] = self::ATTR_ARRAY;
        $this->supportedAttributes['putIn'] = self::ATTR_STRING;
        $this->supportedAttributes['renameFile'] = self::ATTR_ANY;
    }

    public function preinit( & $args )
    {

        /* For safety , remove the POST, GET field !! should only keep $_FILES ! */
        if( isset( $args[ $this->name ] ) ) {
            unset( $_GET[ $this->name ]  );
            unset( $_POST[ $this->name ] );
            unset( $args[ $this->name ]  );
        }
    }

    public function validate($value)
    {
        $ret = (array) parent::validate($value);
        if( $ret[0] == false )
            return $ret;

        // Consider required and optional situations.
        if( @$_FILES[ $this->name ]['tmp_name'] )
        {
            $dir = $this->putIn;
            if( ! file_exists( $dir ) )
                return array( false , _("Directory $dir doesn't exist.") );

            $file = new UploadFile( $this->name );
            if( $this->validExtensions ) {
                if( ! $file->validateExtension( $this->validExtensions ) )
                    return array( false, _('Invalid File Extension: ' . $this->name ) );
            }

            if( $this->sizeLimit )
                if( ! $file->validateSize( $this->sizeLimit ) )
                    return array( false, _("The uploaded file exceeds the size limitation. " . $this->sizeLimit . ' KB.'));
        }
        return true;
    }

    function init( & $args ) 
    {
        /* how do we make sure the file is a real http upload ?
         * if we pass args to model ? 
         *
         * if POST,GET file column key is set. remove it from ->args
         *
         * */
        if( ! $this->putIn )
            throw new Exception( "putIn attribute is not defined." );


        $req = new \Universal\Http\HttpRequest;
        $file = null;

        /* if the column is defined, then use the column 
         *
         * if not, check sourceField.
         * */
        if( isset($req->files[ $this->name ]) ) {
            $file = $req->files[ $this->name ];
        } elseif( $this->sourceField && isset($req->files[ $this->sourceField ]) ) {
            $file = $req->files[ $this->sourceField ];
        }

        if( $file && $file->hasFile() )
        {
            $newName = null;
            if( $this->renameFile ) {
                $cb = $this->renameFile;
                $newName = $cb( $file->name );
            }

            if( $this->putIn && ! file_exists($this->putIn) ) {
                mkdir( $this->putIn, 0755 , true );
            }

            /* if we use sourceField, than use Copy */
            if( $this->sourceField ) {
                $file->copy( $this->putIn , $newName );
            }
            else {
                $file->move( $this->putIn , $newName );
            }

            $args[ $this->name ] = $file->getSavedFilepath();
            $this->action->addData( $this->name , $file->getSavedFilepath() );
        }
    }


}


