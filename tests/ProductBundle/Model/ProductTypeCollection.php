<?php
namespace ProductBundle\Model;



class ProductTypeCollection 
extends \ProductBundle\Model\ProductTypeCollectionBase
{

  public function quantityAvailable() {
      $q = 0;
      foreach( $this as $item ) {
          $q += intval($item->quantity);
      }
      return $q > 0;
  }

    
}
