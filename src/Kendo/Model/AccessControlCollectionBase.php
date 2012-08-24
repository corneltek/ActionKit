<?php
namespace Kendo\Model;



class AccessControlCollectionBase 
extends \LazyRecord\BaseCollection
{

            const schema_proxy_class = '\\Kendo\\Model\\AccessControlSchemaProxy';
        const model_class = '\\Kendo\\Model\\AccessControl';
        const table = 'access_controls';
        
}
