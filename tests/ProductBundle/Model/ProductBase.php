<?php

namespace ProductBundle\Model;


use Maghead\Runtime\Model;
use Maghead\Schema\SchemaLoader;
use Maghead\Runtime\Result;
use Maghead\Runtime\Inflator;
use Magsql\Bind;
use Magsql\ArgumentArray;
use DateTime;
use Maghead\Runtime\ActionCreatorTrait;

class ProductBase
    extends Model
{

    use ActionCreatorTrait;

    const SCHEMA_PROXY_CLASS = 'ProductBundle\\Model\\ProductSchemaProxy';

    const READ_SOURCE_ID = 'master';

    const WRITE_SOURCE_ID = 'master';

    const TABLE_ALIAS = 'm';

    const SCHEMA_CLASS = 'ProductBundle\\Model\\ProductSchema';

    const LABEL = 'Product';

    const MODEL_NAME = 'Product';

    const MODEL_NAMESPACE = 'ProductBundle\\Model';

    const MODEL_CLASS = 'ProductBundle\\Model\\Product';

    const REPO_CLASS = 'ProductBundle\\Model\\ProductRepoBase';

    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductCollection';

    const TABLE = 'products';

    const PRIMARY_KEY = 'id';

    const GLOBAL_PRIMARY_KEY = NULL;

    const LOCAL_PRIMARY_KEY = 'id';

    public static $column_names = array (
      0 => 'id',
      1 => 'name',
      2 => 'subtitle',
      3 => 'sn',
      4 => 'description',
      5 => 'content',
      6 => 'category_id',
      7 => 'is_cover',
      8 => 'sellable',
      9 => 'orig_price',
      10 => 'price',
      11 => 'external_link',
      12 => 'token',
      13 => 'ordering',
      14 => 'hide',
    );

    public static $mixin_classes = array (
    );

    protected $table = 'products';

    public $id;

    public $name;

    public $subtitle;

    public $sn;

    public $description;

    public $content;

    public $category_id;

    public $is_cover;

    public $sellable;

    public $orig_price;

    public $price;

    public $external_link;

    public $token;

    public $ordering;

    public $hide;

    public static function getSchema()
    {
        static $schema;
        if ($schema) {
           return $schema;
        }
        return $schema = new \ProductBundle\Model\ProductSchemaProxy;
    }

    public static function createRepo($write, $read)
    {
        return new \ProductBundle\Model\ProductRepoBase($write, $read);
    }

    public function getKeyName()
    {
        return 'id';
    }

    public function getKey()
    {
        return $this->id;
    }

    public function hasKey()
    {
        return isset($this->id);
    }

    public function setKey($key)
    {
        return $this->id = $key;
    }

    public function removeLocalPrimaryKey()
    {
        $this->id = null;
    }

    public function getId()
    {
        return intval($this->id);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function getSn()
    {
        return $this->sn;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getCategoryId()
    {
        return intval($this->category_id);
    }

    public function isCover()
    {
        $value = $this->is_cover;
        if ($value === '' || $value === null) {
           return null;
        }
        return boolval($value);
    }

    public function isSellable()
    {
        $value = $this->sellable;
        if ($value === '' || $value === null) {
           return null;
        }
        return boolval($value);
    }

    public function getOrigPrice()
    {
        return intval($this->orig_price);
    }

    public function getPrice()
    {
        return intval($this->price);
    }

    public function getExternalLink()
    {
        return $this->external_link;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getOrdering()
    {
        return intval($this->ordering);
    }

    public function isHide()
    {
        $value = $this->hide;
        if ($value === '' || $value === null) {
           return null;
        }
        return boolval($value);
    }

    public function getAlterableData()
    {
        return ["id" => $this->id, "name" => $this->name, "subtitle" => $this->subtitle, "sn" => $this->sn, "description" => $this->description, "content" => $this->content, "category_id" => $this->category_id, "is_cover" => $this->is_cover, "sellable" => $this->sellable, "orig_price" => $this->orig_price, "price" => $this->price, "external_link" => $this->external_link, "token" => $this->token, "ordering" => $this->ordering, "hide" => $this->hide];
    }

    public function getData()
    {
        return ["id" => $this->id, "name" => $this->name, "subtitle" => $this->subtitle, "sn" => $this->sn, "description" => $this->description, "content" => $this->content, "category_id" => $this->category_id, "is_cover" => $this->is_cover, "sellable" => $this->sellable, "orig_price" => $this->orig_price, "price" => $this->price, "external_link" => $this->external_link, "token" => $this->token, "ordering" => $this->ordering, "hide" => $this->hide];
    }

    public function setData(array $data)
    {
        if (array_key_exists("id", $data)) { $this->id = $data["id"]; }
        if (array_key_exists("name", $data)) { $this->name = $data["name"]; }
        if (array_key_exists("subtitle", $data)) { $this->subtitle = $data["subtitle"]; }
        if (array_key_exists("sn", $data)) { $this->sn = $data["sn"]; }
        if (array_key_exists("description", $data)) { $this->description = $data["description"]; }
        if (array_key_exists("content", $data)) { $this->content = $data["content"]; }
        if (array_key_exists("category_id", $data)) { $this->category_id = $data["category_id"]; }
        if (array_key_exists("is_cover", $data)) { $this->is_cover = $data["is_cover"]; }
        if (array_key_exists("sellable", $data)) { $this->sellable = $data["sellable"]; }
        if (array_key_exists("orig_price", $data)) { $this->orig_price = $data["orig_price"]; }
        if (array_key_exists("price", $data)) { $this->price = $data["price"]; }
        if (array_key_exists("external_link", $data)) { $this->external_link = $data["external_link"]; }
        if (array_key_exists("token", $data)) { $this->token = $data["token"]; }
        if (array_key_exists("ordering", $data)) { $this->ordering = $data["ordering"]; }
        if (array_key_exists("hide", $data)) { $this->hide = $data["hide"]; }
    }

    public function clear()
    {
        $this->id = NULL;
        $this->name = NULL;
        $this->subtitle = NULL;
        $this->sn = NULL;
        $this->description = NULL;
        $this->content = NULL;
        $this->category_id = NULL;
        $this->is_cover = NULL;
        $this->sellable = NULL;
        $this->orig_price = NULL;
        $this->price = NULL;
        $this->external_link = NULL;
        $this->token = NULL;
        $this->ordering = NULL;
        $this->hide = NULL;
    }

    public function fetchProductFeatures()
    {
        return static::masterRepo()->fetchProductFeaturesOf($this);
    }

    public function getProductFeatures()
    {
        $collection = new \ProductBundle\Model\ProductFeatureCollection;
        $collection->where()->equal("product_id", $this->id);
        $collection->setPresetVars([ "product_id" => $this->id ]);
        return $collection;
    }

    public function getFeatures()
    {
        $collection = new \ProductBundle\Model\FeatureCollection;
        $collection->joinTable('product_feature_junction', 'j', 'INNER')
           ->on("j.feature_id = {$collection->getAlias()}.id");
        $collection->where()->equal('j.product_id', $this->id);
        $parent = $this;
        $collection->setAfterCreate(function($record, $args) use ($parent) {
           $a = [
              'feature_id' => $record->get("id"),
              'product_id' => $parent->id,
           ];
           if (isset($args['product_features'])) {
              $a = array_merge($args['product_features'], $a);
           }
           return \ProductBundle\Model\ProductFeature::createAndLoad($a);
        });
        return $collection;
    }

    public function fetchProductProducts()
    {
        return static::masterRepo()->fetchProductProductsOf($this);
    }

    public function getProductProducts()
    {
        $collection = new \ProductBundle\Model\ProductProductCollection;
        $collection->where()->equal("product_id", $this->id);
        $collection->setPresetVars([ "product_id" => $this->id ]);
        return $collection;
    }

    public function getRelatedProducts()
    {
        $collection = new \ProductBundle\Model\ProductCollection;
        $collection->joinTable('product_products', 'j', 'INNER')
           ->on("j.related_product_id = {$collection->getAlias()}.id");
        $collection->where()->equal('j.product_id', $this->id);
        $parent = $this;
        $collection->setAfterCreate(function($record, $args) use ($parent) {
           $a = [
              'related_product_id' => $record->get("id"),
              'product_id' => $parent->id,
           ];
           if (isset($args['product_products'])) {
              $a = array_merge($args['product_products'], $a);
           }
           return \ProductBundle\Model\ProductProduct::createAndLoad($a);
        });
        return $collection;
    }

    public function fetchImages()
    {
        return static::masterRepo()->fetchImagesOf($this);
    }

    public function getImages()
    {
        $collection = new \ProductBundle\Model\ProductImageCollection;
        $collection->where()->equal("product_id", $this->id);
        $collection->setPresetVars([ "product_id" => $this->id ]);
        return $collection;
    }

    public function fetchProperties()
    {
        return static::masterRepo()->fetchPropertiesOf($this);
    }

    public function getProperties()
    {
        $collection = new \ProductBundle\Model\ProductPropertyCollection;
        $collection->where()->equal("product_id", $this->id);
        $collection->setPresetVars([ "product_id" => $this->id ]);
        return $collection;
    }

    public function fetchTypes()
    {
        return static::masterRepo()->fetchTypesOf($this);
    }

    public function getTypes()
    {
        $collection = new \ProductBundle\Model\ProductTypeCollection;
        $collection->where()->equal("product_id", $this->id);
        $collection->setPresetVars([ "product_id" => $this->id ]);
        return $collection;
    }

    public function fetchResources()
    {
        return static::masterRepo()->fetchResourcesOf($this);
    }

    public function getResources()
    {
        $collection = new \ProductBundle\Model\ResourceCollection;
        $collection->where()->equal("product_id", $this->id);
        $collection->setPresetVars([ "product_id" => $this->id ]);
        return $collection;
    }

    public function fetchSubsections()
    {
        return static::masterRepo()->fetchSubsectionsOf($this);
    }

    public function getSubsections()
    {
        $collection = new \ProductBundle\Model\ProductSubsectionCollection;
        $collection->where()->equal("product_id", $this->id);
        $collection->setPresetVars([ "product_id" => $this->id ]);
        return $collection;
    }

    public function fetchLinks()
    {
        return static::masterRepo()->fetchLinksOf($this);
    }

    public function getLinks()
    {
        $collection = new \ProductBundle\Model\ProductLinkCollection;
        $collection->where()->equal("product_id", $this->id);
        $collection->setPresetVars([ "product_id" => $this->id ]);
        return $collection;
    }

    public function fetchProductCategories()
    {
        return static::masterRepo()->fetchProductCategoriesOf($this);
    }

    public function getProductCategories()
    {
        $collection = new \ProductBundle\Model\ProductCategoryCollection;
        $collection->where()->equal("product_id", $this->id);
        $collection->setPresetVars([ "product_id" => $this->id ]);
        return $collection;
    }

    public function getCategories()
    {
        $collection = new \ProductBundle\Model\CategoryCollection;
        $collection->joinTable('product_category_junction', 'j', 'INNER')
           ->on("j.category_id = {$collection->getAlias()}.id");
        $collection->where()->equal('j.product_id', $this->id);
        $parent = $this;
        $collection->setAfterCreate(function($record, $args) use ($parent) {
           $a = [
              'category_id' => $record->get("id"),
              'product_id' => $parent->id,
           ];
           if (isset($args['product_categories'])) {
              $a = array_merge($args['product_categories'], $a);
           }
           return \ProductBundle\Model\ProductCategory::createAndLoad($a);
        });
        return $collection;
    }

    public function fetchCategory()
    {
        return static::masterRepo()->fetchCategoryOf($this);
    }
}
