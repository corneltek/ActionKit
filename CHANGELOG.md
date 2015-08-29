CHANGELOGS
===================

Version 2.1 - Sat Aug 29 13:55:39 2015

1. Added `cache_dir` to ServiceContainer.
2. Provided an option for customizing field view class.
3. Added BootstrapFieldView class

Version 2.0.0 - Tue Jun 30 14:23:00 2015

1. Improved action generator to use action template to generate action.
2. Added action templates.
  - File-based action template
  - Record action template
  - Update ordering record action template
2. Added ActionRunner:handleWith method to run action with $_Request directly.
3. Added CSRF token support.
4. Added service container.
5. Added image process.
6. Renamed SortRecordAction to UpdateOrderingRecordAction.
7. Refactored RecordAction options
  - Added options for 'request', 'parent', 'files' 
  - Added ActionRequest for managing $_REQUEST, $_FILES parameters.
8. Raised test coverage to 70%

### Deprecation

- ActionGenerator:generate2
- ActionGenerator:generateRecordAction
- ActionRunner:registerCRUD
- ActionKit/View
- ActionKit/CRUD


Version 1.4 - Fri Apr 25 20:10:02 2014

1. Added SortRecordAction for sorting records.
2. Refactored BulkCopyAction with contentType attribute
3. Added contentType attribute support, currently for "ImageFile" and "File"

Version 1.2 - Sat Dec  7 21:56:54 2013

1. Refactored ManyToManyCheckboxView to support hierarchical data.
2. Improved interface for StackView.

