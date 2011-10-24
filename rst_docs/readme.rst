

Phifty Web Framework
====================

Commands
--------

To init template,cache dirs and permissions

    $ ./phifty init 

To create a new unit test:

    $ ./phifty addtest foo

To create a new plugin:

    $ ./phifty plugin SB -M=Product -M=Order -M=Cart ....


File Structure
--------------

core/ dir:  phifty core action, controller ...etc

Template Structure 
------------------



Synopsis
--------

    webapp()->init(); // run

    webapp()->use('form_widget');
    webapp()->use('smarty');
    webapp()->view();
    webapp()->db();
    webapp()->cache();
    webapp()->currentUser();
    webapp()->config();

    $result = webapp()->actionResult('Result');

    webapp()->model('User');

    .. or

    $u = model('User');
    $users = coln('User');



Naming Convention
=================

Method name (CamalCase)
Function    (under score)
Variable    (CamalCase)
Class name  (CamalCase)


API Methods
===========

with case-delimited function name.


Model/Collection Use Case
=========================

    $foo = new FooModel( 123 );  // get foo with id = 123;
    $foo->load( 123  );   // get foo with id = 123 , limit 1 (if mysql)
    $foo->load_by_cols( array( ) );    // get foo with conditions limit 1 (if mysql)
    $foo->load_by( array( ) );    // get foo with conditions limit 1 (if mysql)
    $foo->update( array(  ) );
        for "null": get column type, 
            is null , or not null
        for "false|true": 
        for "string": 

    $foo->delete( 123 );  // delete with id = 123;
    $foo->delete_by_cols( );  // delete by cols
    $foo->delete_by( );  



    $builder = new ModelSQLBuilder( $model );
    $builder->add_select( "m.*" );
    $builder->join( "table" , "alias" );
    $builder->join_model( );
    $sql = $builder->compile();


    $builder->delete( $id );
    $builder->delete( array( "" => ... , "" => ... ));

    $builder = new ModelSQLBuilder( $model );
    $builder->update( array(  ) );
    $sql = $builder->compile();
    $builder->clear();



Abstract SQL V1.

    $builder->load_by_cols( array(  

        "count" => ">= 10",
        "count" => "> 10",

        "created_by" => $user,  // convert model object to id =>   " created_by = 12 "

        "created_on" => "> date(...)",
        "created_on" =>  new DateTime,    // = '2011-01-01 ....'

        "pending" => false,     // = false
        "pending" => "= FALSE"  // = false
        "deleted" => null ,     // is null
        "deleted" => "IS NULL"   // IS NULL
        "content" => "like '%test%'" 
        "content" => "= 'test'"   // how to escape ?
    ) );

    if value is string, treat as sql string.
    or if column is string type

Abstract SQL V2.

    /* params method is for "select and delete"
    $builder->where( array(  

        "count" => array('>=', 10),
        "count" => 10,

        "created_by" => $user,  // convert model object to id =>   " created_by = 12 "

        "created_on" => array(">" , "date(...)"),
        "created_on" =>  new DateTime,    // = '2011-01-01 ....'

        "pending" => false,     // "= false"
        "pending" => array("=",FALSE)  // "= false"
        "deleted" => null ,     // is null
        "deleted" => array("is",NULL),"IS NULL"   // IS NULL
        "content" => array("like",'%test%'),      // " like '%test%' "
        "content" => array("=",'test')   // how to escape ?

        "created_on" => array('current_timestamp') // raw sql value

        "a1.created_on" => array('current_timestamp')
    ) );

    $builder->where( array( "pending" => false ) );

    $builder->aggStart( "OR" );
    $builder->where( array( "name" => "John" ) );
    $builder->where( array( "name" => "Nick" ) );
    $builder->aggEnd();

    WHERE pending = false OR ( ( name = "John" ) AND ( name = "Nick" ) )
                          ^^^^                                 ^
                          aggStart                             aggEnd



Template Export Vars
--------------------

Env scope:

    {{ Env.request }}
    {{ Env.get }}
    {{ Env.post }}
    {{ Env.session }}
    {{ Env.globals }}
    {{ Env.cookie }}
    {{ Env.server }}
    {{ Env.env }}

AppKernel (Phifty webapp object):
    
    {{ Kernel }}


Rewrite Configuration
---------------------

Lighttpd,

    $HTTP["host"] == "phifty.local" {
        server.document-root = "/Users/c9s/git/lart/phifty/webroot" 
        # url.rewrite-if-not-file = ....
        url.rewrite-once = ( 
            "^/.*\.(php|css|html|htm|pdf|png|gif|jpe?g|js)$" => "$0",
            "^/(.*)" => "/index.php/$1",
        )
    }

Apache rewrite rule

    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-s
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [NC,L]

