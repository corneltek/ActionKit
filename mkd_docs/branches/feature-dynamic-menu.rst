feature-dynamic-menu
====================

Requirement
-----------

MenuItem Link
~~~~~~~~~~~~~
Menu link item can link to a flat page (model).
Menu link item can link to a static link.
Menu link item can link to a dynamic submenu.

MenuItem Folder
~~~~~~~~~~~~~~~
Menu item can be a folder. (Menu folder)

TopMenu
~~~~~~~
- Don't have parent menu item.
- Can be a Folder.
- Can be a Menu Link.

Dynamic menu
~~~~~~~~~~~~
Can mount to TopMenu. (first level)

$productMenu->useLayout('default')

::

    [ ... ] [products] [...]
            [product category1] -> [product1]
            [product category2]    [product2]
            [product category3]    [product3]
                                   [  ...   ]


Synopsis
--------


1. build menu
2. render menu

render:

    @items = dmenu->findTopMenuItems;

    render top menu @items
        render 1-level menuitems (top-down)
        render 2-level menuitems (right the menu)

    render item
        if item.type = 'folder' & level = 1
            render item.subitems for topmenu
        if item.type = 'fodler' & level = 2
            render item.subitems for 1-level menu
            ....
        if item.type = 'link'
            render link to item.link
        if item.type = 'page'
            render item.page
        if item.type = 'html'
            render item.html


Data Structure
--------------

menu_items (id, label, lang, parent, type, data)

for a link menu item:

    id, link name, language, parent, 'link', 'http://.....'

for a folder menu item:

    id, folder name, language, 3312 , 'folder'

for a page menu item:
    id, page title, language, 3312, 'pageId'


Plan
-------------
x. implement javascript menu demo
    webroot/demo/ddsmooth_menu/
    webroot/demo/superfish_menu/
x. implement the menu item model 
x. insert some sample data to mash up with js menu
    - folder
    - link
    - page

* AdminUI Controller.
    to prepare AdminUI data, refactor from current CRUDRouterSet base.
* backend editor
    - folder edit dialog
    - link edit dialog
    - page edit dialog
    - speciel edit dialog
    - sortable + drag/drop interface.
    - can list all menu items on the right panl
        users can drag menu items into the left one.
    - can add a link menu item
        - provide a dialog to enter name and url.
    - can add a folder menu item
    - can add a page menu item
        - provide a dialog to select page to add.
    - can add a products menu item
* for 'dynamic' type menu item: 
    generate/add dynamic menu item to the folder.
* mount to front-end design.

