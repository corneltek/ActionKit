# :vim:filetype=mkd:

# RECORD SPEC

    function schema() {
        $this->hasmany("Comment");

        ....

    }


# ACTION SPEC

## Action has 3 kinds of running mode

* Run mode:

	success or error (or contains validation fail)

* Validation mode:

	return valid or invalid and field arguments...

* Completer mode:

    return field name and a completion list (in hash or string).

## Action data json format:

### Success Message Format:

	{
		success: "Success",
		record:  { },  # when creating/ updating data.
		data: {  },    # extra data fields for actions
	}

### Error Message Format:

	{ 
		error: "Error Message",
		record: {  },   // when only updating record
		validations: {
			"first_name": { error: "Wrong" },
			"last_name" : { error: "" }
		}
	}

### Validation OK:

	{
		validation_ok:  "Message"
	}


### Validation Fail:

	{
		validation_fail: "Message"
	}




## SYNOPSIS

$result = ActionResult->new( );
$result->success( "Success message." )
    ->args( $POST )
    ->data( array(  "data" => 123123 ) )
    ->redirect( ..path.. );

$result->error( "Error message" )
    ->args( $POST )
    ->data( ... )
    ->add_validation( "field" , 
        array(  "warn" => "Warning message", suggest => arrray("better answer")  ) )
    ->add_validation( "field" , 
        array(  "error" => "Error message" , clear => 1 ) )

$result->valid( "Main validation message" )   // optional 
    ->add_validation( "field" , array( "warn" => blah ... ) )

$result->invalid( "Message" )  // optional
    ->add_validation( "field" , array( "ok" ) );


# Completion

$result->completion( "username", "list" ,  array( 1,2,3,4,5 ) );
    ->compStyle( 'default' );

$result->completion( "country",  "dict" ,  array( "h1" => 123, "h2" => 234 ) );

$result->completer( "field", "func" , array( ... args ... ));
