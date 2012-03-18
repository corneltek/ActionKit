Symfony Finder Usage
=====================

    use Symfony\Component\Finder\Finder;
    $finder = Finder::create()->files()->name('*.php')->in(__DIR__);

    $finder = Finder::create()->files()->name('*.php')->in(__DIR__);
    $items = $finder->getIterator();
    foreach( $items as $item ) {
        var_dump( $item );
    }
