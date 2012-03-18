Action.js
=========

Action.js is a component that helps you to submit a form through ajax request.

Action.js also handles File requests (via AIM js library).

A form which is handled by Action.js, has a field named "action":

    <input type="hidden" name="action" value="User::Action::CreateUser"/>

This tells phifty backend to execute which action.

In front-end, we create an action object, and pass the form DOM element object to it,
Action will initialize the form, and handle the submit button to do ajax request.

    var a = new Action;
    a.form( $('form#login') );
    a.onSubmit({ .... });

    // or

    Action.form( $('form#confirmemail') , { status: true } ).onSubmit({ });

In Backend, We use action dispatcher to detemine which action should be executed.

The returned json result will be handled by Action.js, which is like:

    { success: 1 , message: 'Returned message!', data: { .... } }

And, It could be an error message:

    { error: 1 , message: 'Returned message!', data: { .... } }

Config
------

To set global config:

    Action.config({ ....  });

To add plugin (globally) to Action:

    Action.plug( Action_PluginName , { options } );

    Action.form( $('form#confirmemail') , { status: true } ).onSubmit({ });


Action Plugin
-------------

### Register to Action

    Action.plug( ActionMsgbox , {  plugin options } );

    Or via config

    # TODO
    Action.config({
        plugins: [  { plugin: .... , options: { ... } }  ]
    });


### Interface

   plugin.constructor( action , opts )

   (dict)        plugin.dict()
                 i18n dictionary

   (config)      plugin.config( config || null )

   (action)      plugin.action(a)
                    get/set current action.
                    
   (jquery form) plugin.form()
                    get current form from current action.

                 plugin.load()
                    when the form is first initialized with action.

   (form data)   plugin.handleData( formData )
                 plugin.handleResult( result )
                 plugin.handleValidateData( )

                 plugin.onSuccess( result )
                 plugin.onError( result )

                 plugin.onValidate( formData )

   (form data)   plugin.beforeSubmit( formData )
                 plugin.onSubmit( formData )
                 plugin.afterSubmit( result )


### Properties

    this.opts - plugin options
    this.action   - action object

