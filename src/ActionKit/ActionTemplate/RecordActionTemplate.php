<?php
namespace ActionKit\ActionTemplate;
use Exception;
use ActionKit\Exception\UnableToWriteCacheException;
use ClassTemplate\TemplateClassFile;

class RecordActionTemplate implements IActionTemplate
{
    public function register($runner, array $options = array())
    {
        //$ns , $modelName , $types
        if ( isset($options['namespace'])) {
            $ns = $options['namespace'];
        } else {
            throw new Exception('namespace is not defined.');
        }

        if ( isset($options['model'])) {
            $modelName = $options['model'];
        } else {
            throw new Exception('model name is not defined.');
        }

        if ( isset($options['types'])) {
            $types = $options['types'];
        } else {
            throw new Exception('types is not defined.');
        }

        foreach ( (array) $types as $type ) {
            $class = $ns . '\\Action\\' . $type . $modelName;
            $runner->registerWithTemplate( $class, $this->getTemplateName(), [
                'extends' => "\\ActionKit\\RecordAction\\{$type}RecordAction",
                'properties' => [
                    'recordClass' => "$ns\\Model\\$modelName",
                ],
            ]);
        }
    }
    
    public function generate($targetClassName, $cacheFile, array $options = array())
    {
        $templateClassFile = new TemplateClassFile($targetClassName);

        // General use statement
        $templateClassFile->useClass('\\ActionKit\\Action');
        $templateClassFile->useClass('\\ActionKit\\RecordAction\\BaseRecordAction');

        if ( isset($options['extends']) ) {
            $templateClassFile->extendClass($options['extends']);
        }
        if ( isset($options['properties']) ) {
            foreach( $options['properties'] as $name => $value ) {
                $templateClassFile->addProperty($name, $value);
            }
        }
        if ( isset($options['constants']) ) {
            foreach( $options['constants'] as $name => $value ) {
                $templateClassFile->addConst($name, $value);
            }
        }
        if ( false === $templateClassFile->writeTo($cacheFile) ) {
            throw new UnableToWriteCacheException("Can not write action class cache file: $cacheFile");
        }
        return $cacheFile;
    }

    public function getTemplateName()
    {
        return 'RecordActionTemplate';
    }
}