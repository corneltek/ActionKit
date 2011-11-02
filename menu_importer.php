#!/usr/bin/env php
<?php
require 'autoload.php';

use DMenu\Model\MenuItem;
use DMenu\Model\MenuItemCollection;
use Phifty\YAML;
use Phifty\Importer;

class MenuImporter extends Importer
{

    function __construct()
    {
        parent::__construct('import-menu-');
    }
    function traverse_items($lang,$items,$parent = null ,$level = 0) 
    {
        if( empty($items) )
            return;

        foreach( $items as $item ) {
            // is a link
            if( isset( $item['label'] ) ) {

                // create node with parent (if has)
                $menu = new MenuItem;

                if( @$item['link'] ) {
                    $menu->loadOrCreate(array( 
                        'label' => $item['label'],
                        'type'  => 'link',
                        'data'  => @$item['link'],
                        'lang'  => $lang,
                        'parent' => $parent ? $parent->id : 0,
                    ),array('label','data','parent'));
                } else {
                    $menu->loadOrCreate(array( 
                        'label' => $item['label'],
                        'type'  => @$item['type'],
                        'data'  => @$item['data'],
                        'lang'  => $lang,
                        'parent' => $parent ? $parent->id : 0,
                    ),array('label','data','parent'));
                }

                echo str_repeat( ' ' , $level * 4 );
                echo '[M] ' . $item['label'] . " ({$menu->data}) ({$menu->id})\n";
            } 
            // is a folder
            else 
            {
                // parent = item
                foreach( $item as $folder => $subitems ) {
                    // create an folder
                    $folderItem = new MenuItem;
                    $folderItem->loadOrCreate(array(
                        'label' => $folder,
                        'type'  => 'folder',
                        'lang'  => $lang,
                        'parent' => $parent ? $parent->id : 0,
                    ),array('label','parent'));

                    echo str_repeat( ' ' , $level * 4 );
                    echo '[F] ' . $folder . " ({$folderItem->id})\n";
                    $this->traverse_items($lang,$subitems,$folderItem, $level + 1);
                }
            }
        }
    }
    
    function import($file) 
    {
        $langs = YAML::loadFile($file);
        foreach( $langs as $lang => $items ) {
            $this->traverse_items( $lang, $items );
        }
    }
}

$items = new MenuItemCollection;
$items->delete();

$importer = new MenuImporter;
$importer->import( 'menu.yml' );
