<?php
namespace ActionKit;
use ActionKit\ActionGenerator;
use ActionKit\ActionTemplate\CodeGenActionTemplate;

class CRUD
{
    public static function generate($recordClass, $type)
    {
        $generator = new ActionGenerator(array( 'cache' => true ));
        $generator->registerTemplate(new CodeGenActionTemplate('CodeGenActionTemplate'));
        list($modelNs, $modelName) = explode('\\Model\\', $recordClass);
        $modelNs = ltrim($modelNs,'\\');
        $actionFullClass = $modelNs . '\\Action\\' . $type . $modelName;
        $recordClass  = $modelNs . '\\Model\\' . $modelName;
        $baseAction   = $type . 'RecordAction';

        $template = $generator->generate('CodeGenActionTemplate', $actionFullClass, [
            'extends' => '\\ActionKit\\RecordAction\\' . $baseAction,
            'properties' => [
                'recordClass' => $recordClass,
            ],
            'getTemplateClass' => true
        ]);
       if ( class_exists($actionFullClass ,true) ) {
            return $actionFullClass;
        }
        $template->load();
        return $actionFullClass;
    }
}
