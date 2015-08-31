<?php
namespace ProductBundle\Model;
use LazyRecord\BaseModel;
class ProductBase
    extends BaseModel
{
    const schema_proxy_class = 'ProductBundle\\Model\\ProductSchemaProxy';
    const collection_class = 'ProductBundle\\Model\\ProductCollection';
    const model_class = 'ProductBundle\\Model\\Product';
    const table = 'products';
    const read_source_id = 'default';
    const write_source_id = 'default';
    const primary_key = 'id';
    public static $column_names = array (
      0 => 'name',
      1 => 'subtitle',
      2 => 'sn',
      3 => 'description',
      4 => 'content',
      5 => 'category_id',
      6 => 'is_cover',
      7 => 'sellable',
      8 => 'orig_price',
      9 => 'price',
      10 => 'external_link',
      11 => 'token',
      12 => 'ordering',
      13 => 'hide',
      14 => 'id',
    );
    public static $column_hash = array (
      'name' => 1,
      'subtitle' => 1,
      'sn' => 1,
      'description' => 1,
      'content' => 1,
      'category_id' => 1,
      'is_cover' => 1,
      'sellable' => 1,
      'orig_price' => 1,
      'price' => 1,
      'external_link' => 1,
      'token' => 1,
      'ordering' => 1,
      'hide' => 1,
      'id' => 1,
    );
    public static $mixin_classes = array (
    );
    public function getSchema()
    {
        if ($this->_schema) {
           return $this->_schema;
        }
        return $this->_schema = \LazyRecord\Schema\SchemaLoader::load('ProductBundle\\Model\\ProductSchemaProxy');
    }
    public function getName()
    {
            return $this->get('name');
    }
    public function getSubtitle()
    {
            return $this->get('subtitle');
    }
    public function getSn()
    {
            return $this->get('sn');
    }
    public function getDescription()
    {
            return $this->get('description');
    }
    public function getContent()
    {
            return $this->get('content');
    }
    public function getCategoryId()
    {
            return $this->get('category_id');
    }
    public function getIsCover()
    {
            return $this->get('is_cover');
    }
    public function getSellable()
    {
            return $this->get('sellable');
    }
    public function getOrigPrice()
    {
            return $this->get('orig_price');
    }
    public function getPrice()
    {
            return $this->get('price');
    }
    public function getExternalLink()
    {
            return $this->get('external_link');
    }
    public function getToken()
    {
            return $this->get('token');
    }
    public function getOrdering()
    {
            return $this->get('ordering');
    }
    public function getHide()
    {
            return $this->get('hide');
    }
    public function getId()
    {
            return $this->get('id');
    }
}
