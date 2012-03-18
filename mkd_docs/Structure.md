
Application Dir Structure
=========================

Phifty export commmand
====================

init command should parse dirs from `plugins` dir and `app`, `core` dirs.

AppSpace
=================

For action class names like:

    new App\Action\CreateUser
    new App\Action\UpdateUser

should look up actino files in app:

    app/action/CreateUser.php
    app/action/UpdateUser.php

and the names in form:

    App::Action::CreateUser
    App::Action::UpdateUser

for phifty core action:

    Phifty::Action::Redirect


For model names like:

    App::Model::Product

should look up files in app/model

    app/model/Product.php


App Controller

    App\Controller\Index
    App\Controller\AddUser
    App\Controller\RemoveUser


Plugin
===============

* Requirements
** Plugin can inject content to a page header.
** Plugin can require its js or css file from its plugin/web dir.
** Plugin can have its model, controller, action, view (template)

To add a plugin, edit etc/config.yml first.

    - plugins:
        - SB

Your plugin structure is like:

    plugins/sb/Model/
    plugins/sb/Action/
    plugins/sb/Controller/
    plugins/sb/View/
    plugins/sb/config/config.yml (not support yet)

Product class extends from \Phifty\Plugin.

    init()
    pageStart()
    pageEnd()

For action name with plugin

    SB\Action\CreateProduct
    SB\Action\UpdateProduct
    SB\Action\DeleteProduct

should look up

    plugins/SB/Action/CreateProduct.php
    plugins/SB/Action/UpdateProduct.php
    plugins/SB/Action/DeleteProduct.php

For model name with plugin

    SB\Model\Product

should look up:

    plugins/SB/Model/Product.php

Model name with plugin name (Small Business Plugin)

    SB\Model\Product
    SB\Model\ProductCategory
    SB\Model\Order
    SB\Model\OrderItem

    PluginName\Controller\Index
    PluginName\Controller\Back

