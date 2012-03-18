
AppClassLoader
==============

MicroApp ClassLoader

Goal: Should be simple,fast enough.

When searching App\Model\Order should auto mapping to:

    PH_APP_ROOT/App/Model/Order.php
    PH_APP_ROOT/App/Model/Order.php
    PH_APP_ROOT/App/lib/App/SubClass.php

    PH_ROOT/Core/Model/Order.php
    PH_ROOT/Core/Model/OrderCollection.php

NS Mapping

    'App' => PH_APP_ROOT/App,
            PH_APP_ROOT/App/lib
    'Core' => PH_ROOT/Core
            PH_ROOT/Core/lib

For Plugins:

    PH_ROOT/plugins/SB/Model/Order.php
    PH_ROOT/plugins/SB/Action/CreateOrder.php
    PH_ROOT/plugins/Product/Action/CreateProduct.php

NS Mapping

    'SB' => PH_ROOT/plugins/SB, 
            PH_ROOT/plugins/SB/lib


$loader->add( 'App' , PH_APP_ROOT/App );
$loader->add( 'Product' , PH_APP_ROOT/plugins/Product );
