Plans
=======


Controller Improvement
----------------------

* Refactor current router and routerset.
* Retrieve controller mount path from template (view).
* Generate controller URL.
* Pre-filter resource path from template ( baseURL for href and src )
    * Make this as an option.




Cart System
-----------

* Add virtual column to get collection or model data.

* Cart Calculation Interface

* Product Plugin Hierarchal

::

    |-OrderItem
        |-Order
            - Transaction Record.
    |-Cart
    |-Coupon


Controller
----------

* better getMatches design
* re-route to not_found page. (redirect)

OTHERS
------

* For CLI mode, cache base dir should be another... because of permission

* Remove login button from "Page not found".

* Move paths related methods into Phifty\Path class.

* Define exported variables:
    
    phifty.kernel
    phifty.web    => for rendering widget
    phifty.web.widget.text( )

    Problems:

        * render model column labels
        * render collection as a select widget
        * plugin detection
        * render widgets
        * how to include widget js,css ?

* Smart Loader

    $post = M('Post');   // should look for App\Model\Post, {*}\Model\Post.php
    $posts = M('PostCollection');  // should look for App\Model\Post, {*}\Model\PostCollection.php 

* Redesign css/js minifier.

	minify (files) => (file)

* jQuery version should be able to override in one Controller, or in page.

Mixin Model
-----------

* I18N Mixin Model
    * including language chooser
    * including model lang column
    * including lang column select widget

        $this->mixin('\I18n\Model\I18n');

        * trigger beforeCreate,beforeUpdate
        * i18n language choose widget

        $this->mixin('\MetaData\Model\MetaData');

        public $mixins = array();
        $this->mixins[] = $class;
        

Improve Search
--------------

* Search Action

    * needs request arguments 
    * needs action schema

        $act = new SearchAction( $_REQUEST );
        if( $act->run() ) {

        }

    * action schema:
        
        * field search type
            'equal'  => '=',
            'unequal' => '!='
            'include','contains' => 'like %%'
            'exclude' => 'not like % %'

            Action extends Action\SearchRecordAction

            recordClass = ....

            schema() {

                $this->join('created_by');
                $this->column('text')->operator('equal')->required(1);
                $this->column('text')->operator('include')->required(1);
                $this->column('created_by')->from('joined_column');

            }

        * operator can be from outside ?

    * permission check ?
        * action permission
        * per data row permissoin

    * model meta version check


* Support Routers in YAML
* Controller Generator should auto add controller into it.

* When in Action, we return objects like collection or model, these data should be auto-convert into JSON if it's a json request.

* Controller class should be auto built. 
    like NewsList => \.....\.....NewsList

* Have a data register attribute for action.
    return the column value if we need it for js.

* A better collection loader?

* A Image Cover JS Builder.
* A Tag Js Builder.

* The Required validator,
    When Create, A Required column *must* be filled.
    When Update, A Required data column already has a value, it's optional.


* RouterSet should support regexp

* Action validate value (for $\_FILES)
  Action current only use $\_GET and $\_POST as args.


* Interface Routerable
    to Controller,
    to RouterSet,

* Migrate NewsController to News plugin...


Later Stage
-----------

* CSS Gallery http://www.cssdesignawards.com/
    http://www.awwwards.com/web-design-awards/me-oli


* Smarter View Engine (dont repeatly create view engine object, if the
  parameter is the same, use the same engine object );
* Use firePHP for outputing logs.
* Add trigger to page flow and controller, action, events

    page.prepare
    page.render_head
    page.render_body
    page.end

    action.before_run
    action.after_run
    controller.before_run
    controller.after_run

UI Design
---------

* provide a better `not_found` page with 
    Go back button.
    Go back to homepage button.

    admin contact button.
    feedback button.

* provide a better error page.
* provide a better redirect page.
* provide a better not found page.

Backend
-------

* Support OAuth, Twitter, Facebook Login.
* Cache Model Schema
* Provide a Model ProxyFactory.

* Action View  (define form layout)
* create a CRUD view
    * Row UI Widget
        Phifty\UI\Row

* Add filter support. (think about it)
* validate action params (extended params)
* validation message options.

* Provide a Logger
    for ErrorExceptions, E-mail 
    for WarningExceptions, E-mail
    others log it into file.
        should use builtin php function to log
    config: logger options

Mobile Web Todos
----------------

* mobile detect check (add to config)

    mobile_domain: m.site.com

* should mapping correct page to mobile url.





