Conventions
===========

The :doc:`standards` document describes the coding standards for the Phifty
projects and the internal and third-party bundles. 

This document describes coding standards and conventions used in the core
framework to make it more consistent and predictable.

You are encouraged to follow them in your own code, but you don't need to.

Strings
-------

When a string is literal (contains no variable substitutions), the apostrophe
or "single quote" should always be used to demarcate the string:

.. code-block:: php

    <?php
    $a = 'Example String';

String Literals Containing Apostrophes When a literal string itself
contains apostrophes, it is permitted to demarcate the string with
quotation marks or "double quotes". This is especially useful for SQL
statements:


.. code-block:: php

    <?php
    $sql = "SELECT `id`, `name` from `people` "
        . "WHERE `name`='Fred' OR `name`='Susan'";


Variable Substitution
---------------------

Variable substitution is permitted using either of these forms:

.. code-block:: php

    <?php
    $greeting = "Hello $name, welcome back!";
    $greeting = "Hello {$name}, welcome back!";

For consistency, this form is not permitted:

.. code-block:: php

    <?php
    $greeting = "Hello ${name}, welcome back!";

Array
-----

Do not write the array declaration in one line if it's too long (more than 80 chars)

.. code-block:: php

    <?php
    $sampleArray = array( 1, 2, 3, 'String', $a, $b, $c, 56.44, $d, 500 );

You should declare with indentation and new line:

.. code-block:: php

    $sampleArray = array(
        1, 2, 3, 'String',
        $a, $b, $c,
        56.44, $d, 500,
    );


Associative Arrays
------------------
When declaring associative arrays with the Array construct, breaking the
statement into multiple lines is encouraged.

.. code-block:: php

    $sampleArray = array('firstKey'  => 'firstValue',
                         'secondKey' => 'secondValue');


Class
-----

Class names may only contain alphanumeric characters. Numbers are permitted in
class names but are discouraged in most cases. 

An example of acceptable class:

.. code-block:: php

    /**
    * Documentation Block Here
    */
    class SampleClass
    {
        // all contents of class
        // must be indented four spaces
    }

Classes that extend other classes or which implement interfaces should declare their dependencies on the same line when possible.

.. code-block:: php

    class SampleClass extends FooAbstract implements BarInterface
    {
    }

If the class implements multiple interfaces and the declaration exceeds the
maximum line length, break after each comma separating the interfaces, and
indent the interface names such that they align.

.. code-block:: php

    class SampleClass
        implements BarInterface,
                BazInterface
    {

    }

Class name with namespace, namespace should be in CamelCase, and with captical case.

.. code-block:: php

    <?php

    namespace YourApp;

    class ClassLoader {

    }





Method Names
------------

When an object has a "main" many relation with related "things"
(objects, parameters, ...), the method names are normalized:

  * ``get()``
  * ``set()``
  * ``has()``
  * ``all()``
  * ``replace()``
  * ``remove()``
  * ``clear()``
  * ``isEmpty()``
  * ``add()``
  * ``register()``
  * ``count()``
  * ``keys()``

The usage of these methods are only allowed when it is clear that there
is a main relation:

* a ``CookieJar`` has many ``Cookie`` objects;

* a Service ``Container`` has many services and many parameters (as services
  is the main relation, we use the naming convention for this relation);

* a Console ``Input`` has many arguments and many options. There is no "main"
  relation, and so the naming convention does not apply.

For many relations where the convention does not apply, the following methods
must be used instead (where ``XXX`` is the name of the related thing):

+----------------+-------------------+
| Main Relation  | Other Relations   |
+================+===================+
| ``get()``      | ``getXXX()``      |
+----------------+-------------------+
| ``set()``      | ``setXXX()``      |
+----------------+-------------------+
| n/a            | ``replaceXXX()``  |
+----------------+-------------------+
| ``has()``      | ``hasXXX()``      |
+----------------+-------------------+
| ``all()``      | ``getXXXs()``     |
+----------------+-------------------+
| ``replace()``  | ``setXXXs()``     |
+----------------+-------------------+
| ``remove()``   | ``removeXXX()``   |
+----------------+-------------------+
| ``clear()``    | ``clearXXX()``    |
+----------------+-------------------+
| ``isEmpty()``  | ``isEmptyXXX()``  |
+----------------+-------------------+
| ``add()``      | ``addXXX()``      |
+----------------+-------------------+
| ``register()`` | ``registerXXX()`` |
+----------------+-------------------+
| ``count()``    | ``countXXX()``    |
+----------------+-------------------+
| ``keys()``     | n/a               |
+----------------+-------------------+

.. note::

    While "setXXX" and "replaceXXX" are very similar, there is one notable 
    difference: "setXXX" may replace, or add new elements to the relation. 
    "replaceXXX" on the other hand is specifically forbidden to add new 
    elements, but most throw an exception in these cases.


Controll Statements
-------------------

if and else if
~~~~~~~~~~~~~~

Control statements based on the if and elseif constructs must have a single
space before the opening parenthesis of the conditional and a single space
after the closing parenthesis.

Within the conditional statements between the parentheses, operators must be
separated by spaces for readability. Inner parentheses are encouraged to
improve logical grouping for larger conditional expressions.

The opening brace is written on the same line as the conditional statement. The
closing brace is always written on its own line. Any content within the braces
must be indented using four spaces.

.. code-block:: php

    <?php
    if ($a != 2) {
        $a = 2;
    }

Switch
~~~~~~

.. code-block:: php

    <?php
    switch ($numPeople) {
        case 1:
            break;
    
        case 2:
            break;
    
        default:
            break;
    }


Files
-----

Every file that contains PHP code must have a docblock at the top of the file that contains these phpDocumentor tags at a minimum:

.. code-block:: php

    <?php
    /**
    * Short description for file
    *
    * Long description for file (if any)...
    *
    * LICENSE: Some license information
    *
    * @category   Corneltek
    * @package    Phifty
    * @subpackage Sample
    * @copyright  Copyright (c) 2010 Corneltek Inc. (http://corneltek.com)
    * @license    http://corneltek.com/license   MIT License
    * @version    $Id:$
    * @link       http://corneltek.com/
    * @since      File available since Release 1.5.0
    */

The **@category** annotation must have a value of "Corneltek".
  

