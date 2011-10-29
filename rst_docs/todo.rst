BRANCH
======

* etedb 
    * support postgresql
    * support mongodb


* development error page

    * Action not found.
    * Template error
    * Extension not found.
    * Syntax error ... 
    etc.

ideal locale usage:

    webapp()->locale->current;
    webapp()->locale->available;

     

* jQuery context menu integration http://medialize.github.com/jQuery-contextMenu/demo.html
* Move jQuery UI into a web lib.

* etedb row data type casting...

	such like:

	{
		id: "2"
		label: "Test"
		title: null
		lang: null
		parent: "0"
		type: "link"
		data: ""
		sort: "0"
	}

	to 

	{
		id: 2
		label: "Test"
		title: null
		lang: null
		parent: 0
		type: "link"
		data: ""
		sort: 0
	}


Problems
--------

* Improve loading performace.

* Plugin name.

* Optimization Twig loading ... orz

* Update record data with NULL ???

* Pager LinkText

* Plugin class prefix, with namespace. should be re-designed.

* CRUD Generator/Builder.

* ExcelExporter re-design.

x Web::Widget design

* Seperate config into config dir.
  Think about dev, prod and config for other schema...

* Use rst for documentation format

* Find a document generator for generating rst doc.

* Validator re-design

* Action result re-design.

* Action result template support.

* Action button renderer?
* System Action

    * Flush Cache
    * Flush Gettext Cache

* Model Plugins

    var $plugins = array('FakeDelete');

    // then import ModelPlugin_FakeDelete

    class ModelPlugin {
        function schema() {  }
        function before_create() {  }
        function after_create()  {  }
    }


* Action js theme


* Radio Field Render
* Radio Set Render
* Checkbox Field Render
* Support MongoDB / PostgreSQL

Data init for records

    function env_dev_init() {
        $this->load_records( ... );
    }

    function env_prod_init () {
        $this->load_records( ... );
    }


/*
- View Render
- Update Form Render
	- Column Render (TD,TR/DIV)
		- Label Widget
		- Field Widget
			- Text Widget
			- Radios Widget
			- Option Widget
			- Password Widget
			- ReadOnly Widget
			- Plain Widget  (plain text)
			- Date Widget
			- Time Widget
			- DateTime Widget


	$view = new RecordView( $record );
	$view->render_update();
	$view->render_view();

	$view->render_label( 'name' );

	$view->render_field( 'name' , 
			array( 
				"render_type" => "radios",   // plain, image , textarea ...
				"label" => _("Name")
				"nolabel" => 1,
				"valid_values" => [  ],

				// will override column attributes

		   	) , array( class attrs ) );

			// should output td tr or div ??


	// rendering with class
	$view->render_field( 'name' , array( 'render_class' => 'Form::Text' );
	$view->render_field( 'name' , array( 'template' => 'string: .....' );


	$view->render();  # default rendering (default view)

	// render with a wrapper
	$view->render( array( 'template' => 'string:....' )); 

	// wrapper template
	<form>
		{foreach $columns as $column}
			{$view->render_field($column.name)}
		{/foreach}
	</form>
*/

DONE
===================
x Model Join

