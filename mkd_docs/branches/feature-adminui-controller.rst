feature-adminui-controller
======

Requirement
-----------
- Refactor AdminUI Controller
    base data like plugin data.
    template path.

Should not assign variable to controller.

- ReDesign Controller

Controller should have a view object, template variable should assign to it.

Current Use case:

    class Controller {
        function run()
        {
            $this->view()->assign( array( 
                .. => '',
                .. => '',
                .. => '',
                .. => '',
            ));
            return $this->view()->render('template.html', array( ... ) ); /* assign var sugar */

            return $this->render( 'template.html' , array( 
                'var1' => 'var2',
            ) );
        }
    }

AdminUI Controller use case:

    class ManagePage extends AdminUI_Panel  {

        function run()
        {
            /* do something */
            return $this->render( 'page_crud.html' , array( .... ) );
        }

    }

    class Panel extends Controller {

        function init()
        {
            $this->assign( 'reg' , '123' );
        }

    }


Template scope path
~~~~~~~~~~~~~~~~~~~~
This is too complex

Let's make it simpler:

    in App:

        $this->render( 'yasumi/template/user_list.html' );

    in Plugin:

        $this->render( 'plugins/Pages/template/crud_edit_pages.html' );

    in Core:

        $this->render( 'Core/template/user_list.html' );

So we basically have 2 paths:

    PH_APP_ROOT, PH_ROOT

And can provide some helper methods:

    $this->view()->renderPluginTemplate( 'Pages' , 'crud_edit_pages.html' );
    $this->view()->renderAppTemplate( '' );

For JSON, YAML (XXX: thinking)


Plan
----
x refactor current controller to view.
x agg data from current crud router set
- write an workable admin ui panel controller (provide the same template data)
- refactor admin ui css, js to admin ui plugin
- update Changes

Changes
-------
x Change all plugin template rules.
    PH_APP_ROOT , PH_ROOT
x Rename PH_APP_ROOT => PH_APP_ROOT



