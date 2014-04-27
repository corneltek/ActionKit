ActionKit
=========

ActionKit is a library that let you share the business logics across
controllers, pages, ajax requests.

Sometimes, you need to reuse code across your controllers, pages, ajax
requests, you might sit down and write a shared controller class to share the
common code for reuse. This approach might work well for small applications,
when your application is getting bigger and bigger, it will be very complex to
share the common code, and hard to maintain.

ActionKit provides a way to wrap your common code up, and make these common
code reuseable in everywhere in the application.

Besides of sharing the logics across your controllers, you may also define the
parameters with types, validators, form widget type and a lot of parameter
options, and render your Action as a web form.

    [Web Form] => [Input: paramters] => [ Parameter Validation ] 
            => [Execute the logic in Action]  
                => [Return result: Success or Failure, Data: processed data]
                    => [Render result on the web page]

Hence, you don't need to handle the ajax mechanisums, controller handlers,
parameter validations, ActionKit\\Action does all the jobs for you
automatically, so you can focus on the core logics that you only need to
handle.

Action is just like API (application programming interface),
which can be triggered from HTTP requests, Ajax requests, or
from backend, here is the work flow:


               |~~~~~~~~~~~~~~~~~~~~~~~~~~|
               |     Backend PHP          |
               \__________________________/
                          |
                    [Create Action]
                          |
                     [Action View]
                          |
           [Create form widgets from parameters]
                          |
                          |
              [Render Widgets as HTML]
                          |
                          |
                  |~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~|
          /---->  |       Web Forms              |
          |       \______________________________/
          |           |                     |
       (Action.js)    |                  (Action.js)
          |           |                     |
          |       [ HTTP POST Request] [Ajax Request ]
          ^           |                     |
          |           ￬                     ￬
          |           |                     |
          |     /---------------------------------------------\
          |     | Action Runner: dispatch action by Signature |
          |     \---------------------------------------------/
          |                  |
          |                  | 
          |                  ↓
          |       /----------------------------------------\
          |       |        Action                          |
          |       |        +-------------------------------+
          |       |        |  BaseRecordAction             |
          |       |        |  Create, Update, Delete       |
          |       \--------+-------------------------------/
          |           |             |
          |           |             |
          |       [  Argument Filtering ]
          |           |             |
          |           |             |
          |       [  Action Validation  ]
          |           |             |
          ^           |             ↓
          |           |    [Database Operation]
          |           ￬             |
          ^           |             |
          |           |             ↓
          |       [ Action Result, success or fail ]
          ^           |                 |
          |           |                 |
          |       [JSON Response]       |
          |           |                 |
          \--<--<--<--/                 |
                                        |
                        /---------------/
                        |
           /---------------------------\
           |        Controller         |   
           \---------------------------/
                        |
                        |               ☜ You Can Handle Action
                        |                  Results in Controller or Template
                        ￬
                        |
            [ Template Engine, like Twig ]
                        |
                        |
            [ HTML Page with Action Result ] ---->---> [ Web Browser ]


## A Basic Action

A minimal action skeleton:

```php
use ActionKit\Action;
class YourAction extends Action
{
    function run() {
    }
}
```

To use Action, you should define a `run` method at least,
in this `run` method, you write your logics, operations,
then return the result at the end.

To report success result, you can simple use `success`
method:


```php
function run() {
    return $this->success('Success!!');
}
```

You can also pass data to the action result, by appending
another argument in array:

```php
function run() {
    return $this->success('Success', ['user_id' => 1]);
}
```

To report error:

```php
function run() {
    return $this->error('Error', ['user_id' => 1]);
}
```

## Action Signature

To trigger an action from front-end, you can define an
action signature in your HTML form.

When submitting this form, ActionRunner uses this signature
to dispatch your action to the right place.

The convention rule is like below:

- A class with namespace like `App\Action\CreateUser` 
  will be converted to signature `App::Action::CreateUser`.


## A Simple Action Skeleton


```php
class YourAction extends \ActionKit\Action
{

    function schema() {
        $this->param('id')
            ->renderAs('HiddenInput');

        $this->param('password')
            ->renderAs('PasswordInput');

        $this->filterOut('hack','hack2','role');
    }

    function beforeRun() { 
        // do something
    }

    function run()
    {
        return $this->success( 'Success Helper (point to action result object)' );
        return $this->error( 'Error Helper (point to action result object)' );
    }

    function afterRun() 
    {
        // do something
    }
}
```

Then the caller:

```php
$act = new Action( $_REQUEST );
$act->invoke();
$rs = $a->getResult();
```

To take an action, simply call `invoke` method to trigger
the action.

the `invoke` method trigger `runPreinit`, `runInit`,
`beforeRun`, `run`, `afterRun` in order.



Action Schema
-------------

### Synopsis

```php
class Action 
{
    function schema()
    {
        $this->param('id')->type('integer');
        $this->param('title')->type('string');
        $this->param('content')->type('string')->filter( 'html' );  # load html filter
        $this->param('image','Image')   # Image File Column Class  Action\ImageColumn
            ->type('file')              # Image and File column class will auto set type = 'file'
            ->validExtension(array('jpg','png'))
            ->resize( 100 , 100 )
            ->putIn( kernel()->getAppWebDir() . DS . 'public' );
    }
}
```

### Action schema methods


* param(string $columnName):

    create a new action parameter, you can define types,
    widget type, validations, canonicalier ... etc.

* filterOut( array $fieldNames):

    field black list.

    when taking an action to invoke, filter out these
    arguments by array keys.

    when rendering an action with an action view,
    also ignore these params.
    
* takes( array $fieldNames ):

    field white list.

### run method

#### methods

methods that you will need in Action `run` method:

* arg(string $key):
    get argument from action by key.

* setArgs(array $arguments)
    set action arguments.

* success(string $message, $data = array)

    report success message. 
    
    You can also pass data to the action result object.

    By using this method, action object creates an action
    result object, and register the result object into the
    Action Result Pool by using the action signature as a
    key. 

    @see ActionKit\Runner

    * In Ajax mode, the action result will be converted into
      JSON format, and the front-end `Action.js` will get the 
      action result data, then display the result message
      (or dispatch to jGrowl plugin)

    * In HTTP POST/GET mode, the action result is also saved 
      in the Action Result Pool, and by calling a simple twig 
      macro, you can render these action result objects into
      HTML format string.

      @see bundles/CoreBundle/Templates/phifty/action_result.html

* error(string $message)
    report error message.

#### properties

properties that you will need in Action `run` method:

* request: HttpRequest object, you can retrieve 
    POST, GET, SESSION, SERVER through a simple API.


        $this->request->param('user')  
        // is equal to isset($_REQUEST['user']) ? $_REQUEST['user'] : null

        $this->request->server->HTTP_HOST
        // is equal to isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null


### Action param methods


## Action Result

After executing an action, the action creates an action
result object inside itself, you can retrieve the action result
object through the `getResult()` method of action object
to see if it's successfully executed or encountered an error.

Every action result object is saved in the ActionRunner instance.
(the action result pool, which is a singleton object)

You can also fetch action result objects from ActionRunner.

An `ActionResult` object contains a flag (success or error),
a message string, a data stash.

Here is a simple example to check the result error:

```php
if( $result->success ) {

} else {
    // error here

}
```

To get an action result from an action object.

```php
$rs = $action->getResult();
if( $rs->success ) {
    $msg = $rs->getMessage();
    $data = $rs->getData();
}
```

To get an action result from ActionRunner:
    
```php
$runner = ActionKit\Runner::getInstance();
if( $result = $runner->getResult( 'Login::...' ) ) {
    // check the action result

}
```

## RecordAction

Record Action is very useful for connecting ORM with
front-end form, Record Action passes arguments to model
object and validates the arguments from HTTP request.

If all validation passed, and no PDOExcetion was catched, 
the action result will be generated and ready to send
back to front-end.

There are 3 type record actions, which is mapped to CRUD operations:

1. Create
2. Update
3. Delete

The mapped action classes are:

1. CreateRecordAction
2. UpdateRecordAction
3. DeleteRecordAction

Those 3 record action classes inherits BaseRecordAction class.

BaseRecordAction class provides most methods for glueing
ORM interface methods and the result data conversion.

RecordAction Synopsis
----------------------

```php
namespace User\Action\UpdateAction;
use ActionKit\RecordAction\UpdateRecordAction;

class UpdateAction extends UpdateRecordAction {

    function schema() 
    {
        // For record actions, we can convert the record columns
        $this->useRecordSchema();

        $this->param( 'username' )
            ->label( _('Username') )
            ->useSuggestion();

        $this->param( 'password' )
            ->useValidator();

        $this->filterOut(array('auth_token'));
    }

    function validatePassword( $value , $args ) 
    {
        return $this->valid( $message );

        # or
        return $this->invalid( $message );
    }

    function suggestUsername( $value , $args ) {
        return;  # not to suggest
        return $this->suggest( "$value is used. use: " , array( ... ) );
    }

    function completeCountry( $value , $args ) {

        ...
    }
}
```

### Messages

The `BaseRecordAction` provides the default message interface,
to override these messages (for both success and error
message) you can simply override the methods:

```php
function successMessage(OperationResult $ret) {
    return _('Your Success Message');
}

function errorMessage(OperationResult $ret) {
    return _('Your Error Message');
}
```

### RecordAction Examples

CreateNews

```php
namespace News\Action;
use ActionKit\RecordAction\CreateRecordAction;

class CreateNews extends CreateRecordAction
{
    public $recordClass = 'News\Model\News';
}
```

UpdateNews

```php
namespace News\Action;
use ActionKit\RecordAction\UpdateRecordAction;

class UpdateNews extends UpdateRecordAction
{
    public $recordClass = 'News\Model\News';
}
```

### Record Action API

```php
$record = new User\Model\User( 3 ); // primary key = 3
$a = new User\Action\UpdateUser(array( 'nickname' => 'New Name' ) , $record );
$a->invoke();   // which calls $record->update(array( 'nickname' => 'New Name' ) );
```

### RecordAction schema methods

* useRecordSchema


### Record Action Generator

CRUD Actions could be automatically generated, or be manully
created by hands.

To generate CreateRecordAction from a model class name

```php
$g = new ActionKit\ActionGenerator;
$code = $g->generateClassCode( 'App\Model\User' , 'Create' )->code;
```

To generate UpdateRecordAction from a model class name

```php
$g = new ActionKit\ActionGenerator;
$code = $g->generateClassCode( 'App\Model\User' , 'Update' )->code;
```

To generate custom action:

```php
$g = new ActionKit\ActionGenerator;

$g->register('template name','...template path...');

$g->generate('SortImage', 'template name', array(
    "base_class" => "SortRecordAction",
    "record_class" => "ProductBundle\\Model\\ProductImage",
    ... template variable...
));
```


Or even shorter (???):

    use ActionKit\RecordAction\BaseRecordAction;
    $class = BaseRecordAction::createCRUDClass( 'App\Model\Post' , 'Create' );

Or create record actions from record object:

    $post = new Post;
    $update = $post->asUpdateAction();
    $create = $post->asCreateAction();
    $delete = $post->asDeleteAction();


## Action Widget

Action widgets depends on the parameter definition,
the default widget type is TextInput.

    $post = new Post;
    $update = $post->asUpdateAction();
    $html = $update->widget('title')->render();
    $html = $update->widget('title')->render( array( 'class' => '....' ));

    $html = $update->render('title',array( /* attributes.... */ ));
    $html = $update->render( null, array( /* attributes */ )  );

In action schema, the parameters you defined can
generate form widgets (with FormKit) automatically.

What you only to do is to define a `renderAs` attribute for 
your parameters in your action schema.

For example:

    class YourAction extends Action {
        function schema() {
            $this->param('name')
                ->renderAs('TextInput');
        }
    }

And then, to get the form widget through Action object,
you can do:

    $action = new YourAction;
    $widget = $a->widget('name');

And to render it:

    $html = $widget->render(array(  
        'class' => 'extra-class'
        'id' => 'field-id'
    ));

For other type widgets, like SelectInput you can specify
`options`:

        $a->widget('user_type')->render(array( 
            'options' => array(
                'Option 1' => '1'
                'Option 2' => '2'
                'Group Option' => array(
                    'Suboption 1' => '2.1'
                    'Suboption 2' => '2.2'
                )
            )
        ));

You can also force a form widget type for widget method,
which will override the widget type that you defined
previously:

    $a->widget('confirmed','RadioInput')->render(array(
        'false', 'true'
    ));

## Action View

An action view may contains a formkit layout builder, but an action view build
everything for you.

to create an action view, you can simple calls the `createView` method

    $view = $action->createView('+AdminUI\Action\StackView');
    $view->render(array( ... render options ... ));

### Action rendering throught built-in StackView

By using ActionKit StackView, you don't need to write HTML,
the form elements are automatically generated.

Here is a StackView synopsis:

    $action = new SomeWhatAction;
    $view = new ActionKit\View\StackView($action, array( ... options ... ));
    $view->render();

Use case:

    $action = new User\Action\ChangePassword;
    $view = new ActionKit\View\StackView( $action );
    echo $view->render();

And you can render action view via Action's `asView` method:

    echo $action->asView('ActionKit\View\StackView')->render();

    echo $action->asView()->render();  // implies view class ActionKit\View\StackView

So that if you're in Twig template, you can do:

    {{ action.asView('ActionKit\\View\\StackView').render()|raw}}

You can also pass extra options to View class:

    echo $action->asView('ActionKit\View\StackView', array( ... view options ... ))->render();


## Action Rendering (render by pure HTML elements)

You can simply render a HTML form to trigger corresponding
action class, in this example we trigger the
`User\Action\UpdateUser` action, which is generated
automatically through the Dynamic Action Generator.

    <form method="post">
        <!-- action signature -->
        <input type="hidden" name="action"
            value="User::Action::UpdateUser"/>   

        <!-- action fields -->
        <input type="text" name="account" value="c9s"/>

        <input type="submit"/>
    </form>


## Action Rendering and Action.js integration

    <script>
        $(function() {
            Action.form( $('#profile')).setup({
                validation: "msgbox",
                status: true
            });
        });
    </script>

    {{ Web.render_result( update.signature ) |raw}}

    {{ update.asView('ActionKit\\View\\StackView',{ 
            'form_id': 'profile' 
        }).render() |raw }}
    </div>


## Action Rendering (render field by field)

In controller, you can initialize a action object:


    function updateAction() {
        $changePasswordAction = new User\Action\ChangePassword( array( 
            ... values to override field values ... ) , $record );

        return $this->render('some_path.html',array( 
            'changePasswordAction' => $changePasswordAction
        ));
    }

Then in template, you can call action API to render these
fields by these methods, eg `renderSignatureWidget` ,
`renderWidget` , `renderLabel` , `renderSubmitWidget`..etc:

    <form method="post">
        # This renders a field named "action" with action signature "User::Action::ChangePassword" 
        {{ changePasswordAction.renderSignatureWidget |raw}}

        {% if CRUD.Record.id %}
            {{ forms.hidden('id', CRUD.Record.id) }}
        {% endif %}

        <h5>Change/Setup password</h5>

        <div class="v-field">
            <div class="label">{% trans 'Password' %}</div>
            <div class="input">
                {{ changePasswordAction.widget('password1').render() |raw }}
            </div>
        </div>

        <div class="v-field">
            <div class="label">{% trans 'Password' %}</div>
            <div class="input">
                {{ changePasswordAction.widget('password1').render() |raw }}
            </div>
        </div>

        <div class="v-field">
            <div class="label">{% trans 'Password Confirm' %}</div>
            <div class="input">
                {{ changePasswordAction.widget('password2').render() |raw }}
            </div>
        </div>

        <div class="button-group">
            {% if CRUD.Record.id %}
                {{ changePasswordAction.renderSubmitWidget({ class: 'create button', value: _('Save') }) |raw }}
            {% else %}
                {{ changePasswordAction.renderSubmitWidget({ class: 'create button', value: _('Create') }) |raw }}
            {% endif %}
            {{ changePasswordAction.renderButtonWidget({ class: 'button', value: _('Close'), onclick: 'Region.of(this).fadeRemove();' }) |raw}}
        </div>
    </form>

## Front-end Action API

You can execute Actions from front-end, it's more like an API. to send action to execute,
you need to include action.js from action assets.

action.js provides a short helper named `runAction` that helps you to execute Action, you can
call runAction function in following forms:

    runAction( {Action Signature}, {Arguments});
    runAction( {Action Signature}, {Arguments} , {Options});
    runAction( {Action Signature}, {Arguments} , {Options}, {Callback} );
    runAction( {Action Signature}, {Arguments} , {Callback} );
    runAction( {Action Signature}, {Callback} );
    runAction( {Action Signature} );

And in the below example, we send `Stock::Action::DeleteTransaction` to backend with a record id
to delete a transaction record, if it's successful, then fade remove the elements from HTML.

    <div class="txn">
        <div class="txn-status txn-status-{{ txn.status }}">{{ txn.display('status') }}</div>
        <div class="txn-delete">
            <input type="button" 
                onclick=" runAction('Stock::Action::DeleteTransaction', { 
                                id: {{ txn.id }} 
                            }, { 
                                confirm: '確定刪除嗎? ', 
                                remove: $(this).parents('.txn')
                            });" value="刪除"/>
        </div>
    </div>


