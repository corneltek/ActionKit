<?php
namespace User\Model;

class UserCollectionBase  extends \LazyRecord\BaseCollection {
const schema_proxy_class = '\\User\\Model\\UserSchemaProxy';
const model_class = '\\User\\Model\\User';
const table = 'users';

}
