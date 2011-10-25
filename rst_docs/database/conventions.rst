Database Conventions
====================


Table Naming
------------

Phifty framework uses EteDB ORM to generate tables dynamically, the table name
is transformed from Model class name by ``CakePHP\Inflector``.

.. code-block:: php

    <?php

    $inf = Inflector::getInstance();
    return $inf->pluralize( $this->table );

Model names first will be transformed into underscore-separated names, then 
it will be converted with pluralize method to get a plural name.

For example, A sample model class declaration:

.. code-block:: php

    <?php

    namespace Plugin\Model;

    use Phifty\Model;

    class MenuItem extends Model
    {
        function schema() 
        {
            $this->column('label');
            $this->column('link');
        }
    }

The class name of the model is ``Plugin\Model\MenuItem``, the acturall name is MenuItem, so 
the table name will be ``menu_items``.

+------------------------+--------------------------+
| Model Name             | Table name               |
+========================+==========================+
| ``MenuItem``           | ``menu_items``           |
+------------------------+--------------------------+
| ``Product``            | ``products``             |
+------------------------+--------------------------+
| ``ProductCategory``    | ``product_categories``   |
+------------------------+--------------------------+
| ``Entry``              | ``entries``              |
+------------------------+--------------------------+
| ``BlogPost``           | ``blog_posts``           |
+------------------------+--------------------------+
| ``Comment``            | ``comments``             |
+------------------------+--------------------------+


Column naming
-------------

Column names may only contain alphanumeric characters,
Numbers are permitted, but are discouraged in most cases.

* Any model should have a primary key (id) with ``auto_increment``, ``unsigned
  int`` and ``primary key`` attribute. This is generated automatically from 
  EteDB ORM (Corneltek).

* A column, which has a reference to others, should have a suffix of the primary key of the referenced table.


