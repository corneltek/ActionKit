<?php

namespace TestApp\Model;

use Phifty\Model;

class User extends \Phifty\Model
{

    /* 
        Methods could be overrided.

        Array beforeCreate( $args )
        void  afterCreate( $args )

        Array beforeUpdate( $args )
        void  afterUpdate( $args );

        void  beforeDelete()
        void  afterDelete()

    */
    public $table = 'users';

    function schema()
    {
        $this->column( 'email' )->type( 'varchar(300)' );
        $this->column( 'auth_token' )->type( 'varchar(300)' );
        /* 
        $this->column( "price" )->type( 'integer' );
        $this->column( "comment" )->type( 'text' );
        */
    }


}


?>
