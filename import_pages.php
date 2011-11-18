<?php
/*
 * This file is part of the Phifty package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

/*
 * Usage:  
 *
 *  php import_pages.php pages.yml
 *
 * example yaml content:
      ---
      zh_TW:
        -
          title: 其他新產品
          ident: new_products
          file:  pages/new_products_zh-tw.txt
        -
          title: 關於我們
          ident: company
          file:  pages/company_zh-tw.txt
      en:
        -
          title: New Products
          ident: new_products
          file:  pages/new_products_en.txt
        -
          title: Company
          ident: company
          file:  pages/company_en.txt
 *
 */
require 'autoload.php';
use Pages\Model\Page;

list($prog,$file) = $argv;
$data = \Phifty\YAML::load($file);
foreach( $data as $lang => $items ) {
    foreach($items as $item ) {
        if( isset($item['file']) ) {
            $file = $item['file'];
            if( ! file_exists($file) )
                throw new Exception( "$file not found." );
            $content = file_get_contents($item['file']);
            $item['content'] = $content;
            unset($item['file']);
        }
        $args = array_merge(array( 'lang' => $lang ),$item);
        $page = new Page;
        $page->createOrUpdate($args,array('lang','ident'));
        echo "page {$page->lang}/{$page->ident} [{$page->id}] created.\n";
    }
}
