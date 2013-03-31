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
        $this->object->relationships['product_categories']['renderable'] = false;
    }

    public function schema()
    {
        $this->object->useRecordSchema();
    }
}



