Pager
=====

How to use pager:

<?php

    use Phifty\Web\RegionPagerDisplay;
    use Phifty\Web\PagerDisplay;

    $collection = new BookCollection;
    $collection->fetch();

    $pager = $collection->pager( $page , $limit );
    $pagerDisplay = new RegionPagerDisplay($pager);
    $pagerDisplay = new PagerDisplay($pager);

    $items = $pager->items(),  # get items

    echo $pagerDisplay->render();

?>

And the default css style here:

    .pager {  text-align: right; }
    a.pager-link { 
        margin: 1px 2px; 
        color: #74920F;
        text-decoration: none;
    }
    a.pager-link:hover {
        text-decoration: underline;
    }
    a.pager-next { }
    a.pager-prev { }

