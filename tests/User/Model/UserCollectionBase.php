<?php
namespace User\Model;
use LazyRecord\BaseCollection;
class UserCollectionBase
    extends BaseCollection
{
    const schema_proxy_class = 'User\\Model\\UserSchemaProxy';
    const model_class = 'User\\Model\\User';
    const table = 'users';
    const read_source_id = 'default';
    const write_source_id = 'default';
}
