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
        $cursor = $collection->find( array('task' => 'autoload') )->sort(array('created_on' => 1))->limit(20);

        $series = array();

        $categories = array();
        $list = array( 'name' => 'autoload' , 'data' => array() );
        foreach( $cursor as $item ) {
            // $item['created_on']->sec,
            $categories[] = substr($item['commit'],0,5);
            $list['data'][] = $item['duration'];
        }

        $series[] = $list;
        AssetLoader::load('HighCharts');
        return $this->render('apps/TestApp/template/benchmark/index.html', array( 
            'categories' => $categories,
            'series' => $series
        ));
    }
}



