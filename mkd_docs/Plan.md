Plans
=======

Next Stage
----------
* Append 'Controller' to controller class name.
    Core\Controller\NotFound will be Core\Controller\NotFoundController
* Append 'Model' to model class name
    Core\Model\Test will be Core\Model\TestMode.
* Core improvement
* Monolog.

Fast Deployment
---------------
* create an account "production" for deploying site codes.
* deploy an public ssh-key for production
* switch apache site directory.
* refactor apache-siteconfig module.

http://www.saintsjd.com/2011/03/automated-deployment-of-wordpress-using-git/
http://codebork.com/coding/2010/06/03/php-web-deployment-using-git.html


Core Improvement
----------------

* Exception handler for CLI mode.
  * should print friendly error message.
* Exception page for web mode.
  * provide an production mode page (no stacktrace)
  * provide an development mode page (with stacktrace, and can read source code.)


ACL Improvement
---------------


OAuth Integration
-----------------


Continuous Integration
----------------------
* Benchmark::Continuous
* Jenkin Integration
* Doxygen Integration
* rst doc builder
* doc preview web




Benchmark::Continuous
---------------------
(depends on "Fast Deployment")
* benchmark recorder.


AdminUI Improvement
--------------------
x Refactor current admin ui theme.
* can switch theme from config file.
* Integrate with superfish menu.
* Provide an backend menu register or general interface, plugins can register their menu items to the UI.
* Add jQuery tools widget for menu tooltips 
* Add simple slider for backend image preview.


Logging support
---------------
* monolog


User plugin
-----------
* login history.

Action.js framework
-------------------

x Add qunit for unit testing.
x Add testing controller
* Add selenium testing for qunit.

  synopsis:

  without form:

    var a = new Action('User::Action::CreateUser',{ data ... },{ options ...  } );
    a.run();

  with form:

    var a = new Action( $('form') );
    a.onSubmit(function() { ..... });
    a.onError(function() {   });
    a.onSuccess(function() {   });
    a.plug( PluginName );   // add a plugin
    a.setup();              // setup the submit handle to the form


jQuery improvement
------------------
* Use google CDN jQuery loader.

jQuery component widgets
------------------------
* Add jScroll widget.
* Add jQuery tools widget.
* Document those jQuery widget usage:
  with front-end loader, html layout ... etc
* Provide an basic layout for Kate to design.
* Move swfobjects into widgets.
* Improve widget loader function.


Media Improvement
-----------------
* Add attachment support.
* Add External Link support.


Plugin command integration
--------------------------

    $ phifty dmenu:import-menu {arg1} {arg2} {arg3}

    then load:
        plugins/DMenu/Command/ImportMenu.php to run


Web Debug Toolbar
-----------------
* http://www.symfony-project.org/more-with-symfony/1_4/en/07-Extending-the-Web-Debug-Toolbar
* zend framework debug bar: https://github.com/jokkedk/ZFDebug


Router Improvement
------------------
* Support Routers in YAML
* Controller Generator should auto add controller into it.
* RouterSet should support regexp
* Interface Routerable
    to Controller,
    to RouterSet,


Store Plugin
------------

* Add google map and geolocation support.
* Add Store photo model.
    * Make this as an option.

Importers
---------
* Product importer
* Menu importer

Page Content Importer
---------------------
* Depends on plugin command.
* Use CssSelector to import static page content.

    phifty pages:import path/to/page.html

In-place editor
---------------
* thinking...

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


Smart Loader
------------

    $post = M('Post');   // should look for App\Model\Post, {*}\Model\Post.php
    $posts = M('PostCollection');  // should look for App\Model\Post, {*}\Model\PostCollection.php 

Model
-----
http://dealnews.com/developers/php-mysql.html
use mysqli and stmt support for lazy record.

support immutable attribute.

Schema Command
--------------

    phifty schema check [model]
    phifty schema init  { model }  [options]
    phifty schema rebuild { model }  [options]
    phifty schema upgrade

    options
        --stdout       print SQL to stdout.
        --file [file]  print SQL to file.

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



* Action validate value (for $\_FILES)
  Action current only use $\_GET and $\_POST as args.



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

CRUD generator
--------------
* give a model.
    generate a crud class.

Schema Checker
--------------
* check database schema with model schema.
  * list difference
  * update/upgrade schema (?)

OAuth Login
-----------
* GitHub
* Twitter
* Facebook

Backend
-------

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



Reference
=========
* http://symfony.com/doc/2.0/book/index.html
* http://symfony.com/doc/2.0/cookbook/controller/service.html
* http://symfony.com/doc/2.0/book/security.html
* http://symfony.com/doc/2.0/book/routing.html
* http://symfony.com/doc/2.0/book/http_fundamentals.html
