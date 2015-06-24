<?php
namespace ActionKit\ActionTemplate;
use ActionKit\ActionRunner;
use Exception;
use ActionKit\Exception\UnableToWriteCacheException;
use ClassTemplate\TemplateClassFile;

/**
 *  CodeGen-Based Action Template Synopsis
 *
 *      $generator = new ActionKit\ActionGenerator();
 *
 *      // register template to generator
 *      $generator->registerTemplate(new ActionKit\ActionTemplate\CodeGenActionTemplate);
 *
 *      // load template by name
 *      $template = $generator->loadTemplate('CodeGenActionTemplate');
 *
 *      $runner = new ActionKit\ActionRunner;
 *      // register action to template
 *      $template->register($runner, array(
 *           'namespace' => 'test',
 *           'model' => 'testModel',
 *           'types' => array('Create','Update','Delete','BulkDelete')
 *      ));
 *
 *      $className = 'test\Action\UpdatetestModel';
 *
 *      // generate action from template
 *      $cacheFile = $generator->generate('CodeGenActionTemplate',
 *          $className,
 *          $runner->dynamicActions[$className]['actionArgs']);
 *
 *      require $cacheFile;
 *
 */
class CodeGenActionTemplate implements IActionTemplate
{
    /**
     * @synopsis
     *
     *    $template->register($runner, array(
     *        'namespace' => 'test',
     *        'model' => 'testModel',   // model's name
     *        'types' => array('Create','Update','Delete','BulkDelete')
     *    ));
     */
    public function register(ActionRunner $runner, array $options = array())
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
            $runner->register( $class, $this->getTemplateName(), [
                'extends' => "\\ActionKit\\RecordAction\\{$type}RecordAction",
                'properties' => [
                    'recordClass' => "$ns\\Model\\$modelName",
                ],
            ]);
        }
    }
    
    /**
     * @synopsis
     *
     *    $cacheFile = $generator->generate('CodeGenActionTemplate',
     *       'test\Action\UpdatetestModel',
     *       [
     *           'extends' => "\\ActionKit\\RecordAction\\CreateRecordAction",  
     *           'properties' => [
     *               'recordClass' => "test\\testModel\\$modelName",    // $ns\\Model\\$modelName
     *           ],
     *           'getTemplateClass' => true  // return TemplateClassFile directly
     *       ]
     *    );
     */
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

        if (isset($options['getTemplateClass']) && $options['getTemplateClass']) {
            return $templateClassFile;
        }

        if ( false === $templateClassFile->writeTo($cacheFile) ) {
            throw new UnableToWriteCacheException("Can not write action class cache file: $cacheFile");
        }
        return $cacheFile;
    }

    public function getTemplateName()
    {
        return 'CodeGenActionTemplate';
    }
}