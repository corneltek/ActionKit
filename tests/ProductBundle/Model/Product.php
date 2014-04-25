<?php
namespace ProductBundle\Model;
use ProductBundle\Model\ProductCollection;
use ProductBundle\Model\ProductTypeCollection;
use ProductBundle\Model\ProductImageCollection;
use ProductBundle\Model\ResourceCollection;
use ActionKit\ColumnConvert;

class Product extends \ProductBundle\Model\ProductBase
{


    public function dataLabel()
    {
        /*
        if ( $this->lang ) {
            return '[' . _($this->lang) . '] ' .  $this->name;
        }
        */
        return $this->name;
    }

    public function beforeUpdate($args) {
        $args['updated_on'] = date('c');
        return $args;
    }

    public function availableTypes() {
        return $this->types->filter(function($type) {
            return $type->quantity > 0;
        });
    }

    public function renderThumb($attrs = array()) {
        $html = "<img src=\"/{$this->thumb}\"" ;
        $attrs = array_merge(array(
            'title' => $this->name,
            'alt'   => $this->name,
        ), $attrs);
        foreach( $attrs as $key => $val ) {
            $html .= " $key=\"" . addslashes($val) . "\"";
        }
        $html .= "/>";
        return $html;
    }

    public function renderImage($attrs = array()) {
        $html = "<img src=\"/{$this->image}\"" ;
        foreach( $attrs as $key => $val ) {
            $html .= " $key=\"" . addslashes($val) . "\"";
        }
        $html .= "/>";
        return $html;
    }

    public function getPageKeywords() {  }

    public function getPageDescription() { }

    public function getPageTitle() {
        $title = $this->name;
        if ($this->sn) {
            $title .= ' - ' . $this->sn;
        }
        return $title;
    }


    /**
     * @return bool check price and sellable flag.
     */
    public function isSellable() {
        return $this->sellable && $this->price > 0;
    }


    protected $_allSoldOut;

    public function isAllSoldOut() {
        if ( $this->_allSoldOut !== null ) {
            return $this->_allSoldOut;
        }
        return $this->_allSoldOut = ! $this->types->quantityAvailable();
    }

}
