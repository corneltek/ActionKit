Quick Start
===========


Requirement
-----------

Mac OS
~~~~~~

* git-core
* php5-intl
* php5-mbstring
* php5-gettext
* php5-apc
* php5-mysql

Install phifty bootstrap script
-------------------------------

.. code-block:: bash

    git clone git@git.corneltek.com:phifty.git
    cp phifty/phifty /usr/bin/

Create an application
---------------------

To create the default skeleton of an application, run the commands below:

.. code-block:: bash

    mkdir yourApp
    cd yourApp
    phifty init yourApp

The application structure is like:

:: 

    yourApp/
    yourApp/Controller/
    yourApp/Model/
    yourApp/Action/
    yourApp/lib/yourApp/
    yourApp/template/
    config/app.yml
    config/dev.yml
    webroot/
    phifty/
    define.php
    autoload.php

``phifty/`` is a git submodule of phifty framework.

``yourApp`` contains your application code.

``webroot`` your http entry point, the document root path of apache should be set here.


Then edit the config located in ``config/dev.yml`` for your development.

Edit your `/etc/hosts` to add your apache virtual host.

Setup an apache virtual host to the ``yourApp/webroot/`` directory.


To create a unit test
---------------------

.. code-block:: bash

    phifty/phifty addtest foo

To create a plugin
------------------

.. code-block:: bash

    phifty/phifty plugin SB -M=Product -M=Order -M=Cart ....

