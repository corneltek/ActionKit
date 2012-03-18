Frontend Action.js
==================

## methods


### submit

submit the form action

    .submit()
    .submit( [options] , [callback] );
    .submit( [callback] );

    Action.form(this).submit( function(resp) { 
        ...
    });

#### options

    redirect:
    delay:

    refresh
    refreshParent

    Event Handlers:

    context is action object.

        beforeSubmit( form element, form data )
        afterSubmit( form element, action result )
        onSubmit( form element, form data )

        onDone( form element )

        onUpload( form element, files )
        beforeUpload
        afterUpload


#### Example

    onSubmit: function( fm, data ) {
        var fm = this.form();
        ...
    }















   Action.form(this).submit();
   Action.form(this).submitWith();

   (new Action).run( 'Delete' , {  } );
   (new Action).setForm(this).submit();

To show action status message:

    Create a div:

        <div class="action-result"></div>

    Or, use options() method to create a status div by script.

        .options({ status: true })

To use action message div.

    Insert a div add class named "action-result"
    then the error messages will display inner
    the div block.

    <div class="action-result">
        <div id="errors">
            <div class="error"> </div>
            <div class="error"> </div>
            ...
        </div>
        <div id="messages">
            <div class="message"></div>
            <div class="message"></div>
            <div class="message"></div>
            ...
        </div>
    </div>

Or use an option to create message div dynamically.

    $(document.body).ready(function() {
        Action.form( $('#create_user') ).options({ 
            validation: true,
            validation: "perfield"
        }).onSubmit({ refreshWithID: $('#create_user') });
    });

    Action.setValidateStyle("perfield");  // show messages after each fields.
    Action.setValidateStyle("msgbox");    // show messages in one message box. but also highlight error,validated fields.



Run Form Action with callback:

    $(function() {
        Action.form( $('form#product-image') ).options({
            validation: "msgbox",
            status: true,
            }).onSubmit({ clear: true } , function(data) {  
                // console.log( data );
                
            });
    });

New Design
==========

Action.config({
	vstyle: "msgbox",
	logger: true
});




Action.log( "Message" , data );

	use console.log as default
		console.log.apply( console, [ "message" , { a: 123 } ] );



