<?php
namespace User\Model;

class UserBase  extends \LazyRecord\BaseModel {
const schema_proxy_class = 'User\\Model\\UserSchemaProxy';
const collection_class = 'User\\Model\\UserCollection';
const model_class = 'User\\Model\\User';
const table = 'users';

}
