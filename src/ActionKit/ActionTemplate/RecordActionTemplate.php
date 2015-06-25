<?php
namespace ActionKit\ActionTemplate;
use ActionKit\ActionRunner;
use ActionKit\ActionTemplate\CodeGenActionTemplate;
use ActionKit\GeneratedAction;
use ClassTemplate\TemplateClassFile;


class RecordActionTemplate extends CodeGenActionTemplate
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
        if (!isset($options['namespace'])) {
            throw new RequiredConfigKeyException('namespace', 'namespace of the generated action');
        }
        if (!isset($options['model'])) {
            throw new RequiredConfigKeyException('model', 'required for creating record actions');
        }
        if (! isset($options['types'])) {
            throw new RequiredConfigKeyException('types', 'types is an array of operation names for CRUD');
        }

        foreach ( (array) $options['types'] as $type ) {
            $actionClass = $options['namespace'] . '\\Action\\' . $type . $options['model'];
            $configs = [
                'extends' => "\\ActionKit\\RecordAction\\{$type}RecordAction",
                'properties' => [
                    'recordClass' => $options['namespace'] . "\\Model\\" . $options['model'],
                ],
            ];
            if (isset($type['allowedRoles'])) {
                $configs['allowedRoles'] = $type['allowedRoles'];
            }
            $runner->register($actionClass, $asTemplate, $configs);
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

        $this->initGenericClassWithOptions($templateClassFile, $options);

        // check current role == expected role
        // 這個 expecting role 可以是一個 ActionTemplate 的參數
        // 如果有 "expecting_role" 的參數的時候，才去產生 currentUserCan 這個 method 做 override
        if ( isset($options['allowedRoles'])) {
            $templateClassFile->addProperty('allowedRoles', $options['allowedRoles']);
            $body = <<<EOF
                if (is_string(\$user)) {
                    return in_array(\$user, \$this->allowedRoles);
                } else if (\$user instanceof Kendo\Acl\MultiRoleInterface  || method_exists(\$user,'getRoles')) {
                    foreach (\$user->getRoles() as \$role ) {
                        if (in_array(\$role, \$this->allowedRoles) )
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


