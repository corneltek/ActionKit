<?php
namespace ActionKit\Model\CRUDTest;

class FooUserCollectionBase  extends \Maghead\Runtime\Collection {
const schema_proxy_class = '\\ActionKit\\Model\\CRUDTest\\FooUserSchemaProxy';
const model_class = '\\ActionKit\\Model\\CRUDTest\\FooUser';
const table = 'foo_users';

}
