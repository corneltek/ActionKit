
# Changes

六  3/17 13:53:37 2012

- Rename plugins CRUD to {crud id}/edit.html 
- Rename widgets to assets
- Change CRUD.Edit.Record => CRUD.Record

二 12/27 14:38:33 2011

- Remove webapp()->currentUser() usage.

六 11/26 03:24:25 2011

News plugin:
- remove icon image column.
- add thumb column.
- add image column.
- rename with_icon option to with_image.

六 11/ 5 11:51:28 2011

- Change route api and refactor route dispatcher.

** model changes

- news plugin: is_cover column.
- usecase plugin: lang columns.
- product plugin: hide, token column.

五 10/28 19:30:28 2011

- Change plugin paths:  core to Core/,  core/lib/Core.php Core/Core.php
- Fix tinyMCE bugs

日 10/23 22:01:04 2011

- Rename model->data to model->_data
- provide model->getData method
- Change model->label to protected.

三  9/28 13:50:17 2011

### Feature-iconnews branch

- Change plugin config setter, getter: {{plugin}}::getInstance()->config( "key" );
  - Add {{plugin}}::getInstance()->configHash
  - Since static variable doesn't work for subclasses...
- Use Exporter to export vars to template
	- remove phifty scope.
- Add locale command to merge po files from framework.
- Import Twig Text extension.
- Fix AdminUI menu, div blocks can't be show in IE.
- Add User CRUD Pages, Password Change, Edit, Update, Create.
- Improve UniversalClassLoader.

### Config improvement
- load config/app.yml and config/app_site.yml first.

    appname => app_id
    sitename => app_name
    
- then load config/dev.yml or config/prod.yml or config/testing.yml


0.3
	* change validParis label => key   to key => lable.
	* use inflator, table name should plural (***important***)
	* change microapp structure.

		app/Model
		app/Controller
		app/...
		app/...

0.2
	* Action js refactor
	* Action PHP Class refactor
