Coding Standards
================

This document provides guidelines for code formatting and documentation to
individuals and teams contributing to Phifty Framework. Many developers using
Phifty Framework have also found these coding standards useful because their
code's style remains consistent with all Phifty Framework code. It is also worth
noting that it requires significant effort to fully specify coding standards.


.. note::

    Sometimes developers consider the establishment of a standard more
    important than what that standard actually suggests at the most detailed level
    of design. The guidelines in Phifty Framework's coding standards capture
    practices that have worked well on the Phifty Framework project. You may modify
    these standards or use them as is in accordance with the terms of our license. 

When contributing code to Corneltek, you must follow its coding standards. To
make a long story short, here is the golden rule: **Imitate the existing
Corneltek code**.  Most open-source bundles and libraries used by Corneltek also
follow the same guidelines, and you should too.

Remember that the main advantage of standards is that every piece of code
looks and feels familiar, it's not about this or that being more readable.

Since a picture - or some code - is worth a thousand words, here's a short
example containing most features described below:

.. code-block:: php

    <?php

    /*
     * This file is part of the Corneltek package.
     *
     * (c) Yo-An Lin <pedro@corneltek.com>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

    namespace Acme;

    class Foo
    {
        const SOME_CONST = 42;

        private $foo;

        /**
         * @param string $dummy Some argument description
         */
        public function __construct($dummy)
        {
            $this->foo = $this->transform($dummy);
        }

        /**
         * @param string $dummy Some argument description
         * @return string|null Transformed input
         */
        private function transform($dummy)
        {
            if (true === $dummy) {
                return;
            }
            if ('string' === $dummy) {
                $dummy = substr($dummy, 0, 5);
            }

            return $dummy;
        }
    }

Structure
---------

* Never use short tags (`<?`);

* Don't end class files with the usual `?>` closing tag;

* Indentation is done by steps of four spaces (tabs are never allowed);

* Use the linefeed character (`0x0A`) to end lines;

* Add a single space after each comma delimiter;

* Don't put spaces after an opening parenthesis and before a closing one;

* Add a single space around operators (`==`, `&&`, ...);

* Add a single space before the opening parenthesis of a control keyword
  (`if`, `else`, `for`, `while`, ...);

* Add a blank line before `return` statements, unless the return is alone
  inside a statement-group (like an `if` statement);

* Don't add trailing spaces at the end of lines;

* Use braces to indicate control structure body regardless of the number of
  statements it contains;

* Put braces on their own line for classes, methods, and functions
  declaration;

* Separate the conditional statements (`if`, `else`, ...) and the opening
  brace with a single space and no blank line;

* Declare visibility explicitly for class, methods, and properties (usage of
  `var` is prohibited);

* Use lowercase PHP native typed constants: `false`, `true`, and `null`. The
  same goes for `array()`;

* Use uppercase strings for constants with words separated with underscores;

* Define one class per file;

* Declare class properties before methods;

* Declare public methods first, then protected ones and finally private ones.


PHP File Formating
------------------

General
~~~~~~~

PHP code must always be delimited by the full-form, standard PHP tags:

.. code-block:: php

    <?php

    ?>

Indentation
~~~~~~~~~~~

Indentation should consist of 4 spaces. Tabs are not allowed.

Vim users can use the config below:

.. code-block:: vim

    :set expandtab sw=4 tabstop=4 softtabstop=4

Maximum Line Length
~~~~~~~~~~~~~~~~~~~

The target line length is 80 characters. 
That is to say, Phifty Framework developers should strive keep each line of
their code under 80 characters where possible and practical. However, longer
lines are acceptable in some circumstances. The maximum length of any line of
PHP code is 120 characters.


Line Termination
~~~~~~~~~~~~~~~~

Line termination follows the Unix text file convention. Lines must end with a single linefeed (LF) character. Linefeed characters are represented as ordinal 10, or hexadecimal 0x0A.

Note: Do not use carriage returns (CR) as is the convention in Apple OS's (0x0D) or the carriage return - linefeed combination (CRLF) as is standard for the Windows OS (0x0D, 0x0A).

Naming Conventions
------------------

Simple Rules
~~~~~~~~~~~~

* Use camelCase, not underscores, for variable, function and method
  names;

* Use underscores for option, argument, parameter names;

* Use namespaces for all classes;

* Suffix interfaces with `Interface`;

* Use alphanumeric characters and underscores for file names;

Don't forget to look at the more verbose :doc:`conventions` document for
more subjective naming considerations.


Documentation
-------------

* Add PHPDoc blocks for all classes, methods, and functions;

* Omit the `@return` tag if the method does not return anything;

* The `@package` and `@subpackage` annotations are not used.

License
-------

* Phifty is released under the MIT license, and the license block has to be
  present at the top of every PHP file, before the namespace.


About
-----
This documentation is refered from Symfony's coding standard documentation.

