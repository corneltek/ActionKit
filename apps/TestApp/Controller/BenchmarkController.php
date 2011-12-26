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
namespace TestApp\Controller;
use Phifty\Controller;
use Phifty\Asset\AssetLoader;
use Mongo;


class BenchmarkController extends Controller
{
    function indexAction()
    {

        $m = new Mongo;
        $db = $m->benchmarks;
        // select a collection (analogous to a relational database's table)
        $collection = $db->phifty;


        $categories = array();
        $series = array();

        // load task
        $cursor = $collection->find( array('task' => 'autoload') )->sort(array('created_on' => -1)); // ->limit(100);
        $list = array( 'name' => 'autoload' , 'data' => array() );
        foreach( $cursor as $item ) {
            // $item['created_on']->sec,
            $categories[] = substr($item['commit'],0,5);
            $list['data'][] = $item['duration'];
        }


        // load task
        $cursor = $collection->find( array('task' => 'main') )->sort(array('created_on' => -1)); // ->limit(100);
        $list2 = array( 'name' => 'main' , 'data' => array() );
        foreach( $cursor as $item ) {
            // $item['created_on']->sec,
            // $categories[] = substr($item['commit'],0,5);
            $list2['data'][] = @$item['duration'];
        }

        // pad for task1
        {
            $size = count($list['data']);
            $list2['data'] = array_pad($list2['data'], $size , 0 );
        }

        $list['data'] = array_reverse( $list['data'] );
        $series[] = $list;

        $list2['data'] = array_reverse( $list2['data'] );
        $series[] = $list2;

        $categories = array_reverse( $categories );

        AssetLoader::load('jquery');
        AssetLoader::load('HighCharts');
        return $this->render('apps/TestApp/template/benchmark/index.html', array( 
            'categories' => $categories,
            'series' => $series
        ));
    }
}



