<?php
namespace User\Model;
use LazyRecord\BaseCollection;
class UserCollectionBase
    extends BaseCollection
{
    const SCHEMA_PROXY_CLASS = 'User\\Model\\UserSchemaProxy';
    const MODEL_CLASS = 'User\\Model\\User';
    const TABLE = 'users';
    const READ_SOURCE_ID = 'default';
    const WRITE_SOURCE_ID = 'default';
    const PRIMARY_KEY = 'id';
}
