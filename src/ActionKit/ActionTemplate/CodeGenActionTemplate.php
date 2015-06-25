<?php
namespace ActionKit\ActionTemplate;
use ActionKit\ActionRunner;
use ActionKit\GeneratedAction;
use ActionKit\Exception\RequiredConfigKeyException;
use Exception;
use ClassTemplate\TemplateClassFile;

/**
 *  CodeGen-Based Action Template Synopsis
 *
 *      $actionTemplate = new CodeGenActionTemplate();
 *      $runner = new ActionKit\ActionRunner;
 *      $actionTemplate->register($runner, 'CodeGenActionTemplate', array(
 *          'namespace' => 'test2',
 *          'model' => 'test2Model',   // model's name
 *          'types' => array('Create','Update','Delete','BulkDelete')
 *      ));
 *
 *      $className = 'test2\Action\UpdatetestModel';
 *      $generatedAction = $actionTemplate->generate($className, [
 *          'extends' => "\\ActionKit\\RecordAction\\CreateRecordAction",
 *          'properties' => [
 *              'recordClass' => "test2\\Model\\testModel",
 *          ],
 *      ]);
 *
 *      $generatedAction->requireAt($cacheCodePath);
 *
 */
class CodeGenActionTemplate implements ActionTemplate
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
    public function register(ActionRunner $runner, $asTemplate, array $options = array())
    {
        //$ns , $modelName , $types
        if (!isset($options['namespace'])) {
            throw new RequiredConfigKeyException('namespace is not defined.');
        }
        $ns = $options['namespace'];

        if (!isset($options['model'])) {
            throw new RequiredConfigKeyException('model name is not defined.');
        }
        $modelName = $options['model'];

        if (! isset($options['types'])) {
            throw new RequiredConfigKeyException('types is not defined.');
        }
        $types = $options['types'];

        foreach ( (array) $types as $type ) {
            $class = $ns . '\\Action\\' . $type['name'] . $modelName;
            $configs = [
                'extends' => "\\ActionKit\\RecordAction\\{$type['name']}RecordAction",
                'properties' => [
                    'recordClass' => "$ns\\Model\\$modelName",
                ],
            ];
            if (isset($type['allowedRoles'])) {
                $configs['allowedRoles'] = $type['allowedRoles'];
            }
            $runner->register( $class, $asTemplate, $configs);
        }
    }
    
    /**
     * @synopsis
     *
     *    $generatedAction = $template->generate('test\Action\UpdatetestModel',
     *       [
     *           'extends' => "\\ActionKit\\RecordAction\\CreateRecordAction",  
     *           'properties' => [
     *               'recordClass' => "test\\testModel\\$modelName",    // $ns\\Model\\$modelName
     *           ],
     *           'getTemplateClass' => true  // return TemplateClassFile directly
     *       ]
     *    );
     */
    public function generate($actionClass, array $options = array())
    {
        $templateClassFile = new TemplateClassFile($actionClass);

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

        // check current role == expected role
        // 這個 expecting role 可以是一個 ActionTemplate 的參數
        // 如果有 "expecting_role" 的參數的時候，才去產生 currentUserCan 這個 method 做 override
        if ( isset($options['allowedRoles'])) {
            $templateClassFile->addProperty('allowedRoles', $options['allowedRoles']);
            $body = <<<EOF
                if(is_string(\$user)) {
                    return in_array(\$user, \$this->allowedRoles);
                } else if(\$user instanceof MultiRoleInterface  || method_exists(\$user,'getRoles')) {
                    foreach( \$user->getRoles() as \$role ) {
                        if( in_array(\$role, \$this->allowedRoles) )
                            return true;
                    }
                    return false;
                }
EOF;
            $templateClassFile->addMethod('public','currentUserCan', ['$user'] , $body);
        }

        $code = $templateClassFile->render();
        return new GeneratedAction($actionClass, $code, $templateClassFile);
    }
}
