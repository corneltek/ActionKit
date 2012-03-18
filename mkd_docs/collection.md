Collection
==========

<?php

    use BookCollection;
    $items = new BookCollection;
    $arrayItems = $items->items();   # fetch and return item array.

?>

Full match

<?php

    use BookCollection;
    $items = new BookCollection;
    $items->where(array( 'author' => 'John' ));

?>

<?php

    use BookCollection;
    $items = new BookCollection;
    $items->where(array( 'reads' => 100 ));  # where reads = 100

    $items->where(array( 'reads' => array('<',100) ));  # where reads < 100
    $items->where(array( 'reads' => array('>',100) ));  # where reads > 100

?>

String like:
    
<?php

    use BookCollection;
    $items = new BookCollection;
    $items->where(array( 'author' => array('like','%John%') ));

    $items->where(array( 'created_on' => array('date("....")') )  # insert a raw SQl statement.

?>
