<?php
namespace Product\Action;

class ProductBaseMixin
{
    public $plugin;

    /**
     * The object to mixin
     */
    public $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    public function preinit()
    {
        /**
         * TODO:  Note that the self_key is pointing the related class currently.
         *        We want to make self_key to point the action record itself.
         */
        $this->object->nested = true;
        $this->object->relationships = array(
            'types' => array(
                'has_many' => true,
                'record' => 'Product\\Model\\ProductType',
                'self_key' => 'product_id',
                'foreign_key' => 'id',
            ),
        );

        $this->object->relationships['product_categories'] = array(
            'has_many'    => true,
            'record'      => 'Product\\Model\\ProductCategory',
            'self_key'    => 'product_id',
            'foreign_key' => 'id',
            'renderable'  => false,
        );
        $this->object->relationships['categories'] = array(
            'many_to_many'    => true,
            // required from editor
            'collection'      => 'Product\\Model\\CategoryCollection',
            'label'           => 'name',

            // for inter relationship processing
            'from'            => 'product_categories',
            'inter_foreign_key' => 'category_id',
            'filter' => function($collection, $record) {
                $collection->order('lang','desc');
                return $collection;
            },
        );
    }

    public function schema()
    {
        $this->object->useRecordSchema();
    }
}



