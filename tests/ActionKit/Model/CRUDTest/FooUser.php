<?php
namespace ActionKit\Model\CRUDTest;

class FooUser extends \LazyRecord\BaseModel {
    public function schema($schema) 
    {
        $schema->column('username')->varchar(12);
        $schema->column('password')->varchar(12);
    }
#boundary start 2d278467a6071e8ac2130d201b3510e1
	const schema_proxy_class = 'ActionKit\\Model\\CRUDTest\\FooUserSchemaProxy';
	const collection_class = 'ActionKit\\Model\\CRUDTest\\FooUserCollection';
	const model_class = 'ActionKit\\Model\\CRUDTest\\FooUser';
	const table = 'foo_users';
#boundary end 2d278467a6071e8ac2130d201b3510e1
}

