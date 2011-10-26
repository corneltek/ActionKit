<?php
$archive = new Phar('twig.phar', 0, 'twig.phar');
$archive->setStub('<?php
Phar::mapPhar();
__HALT_COMPILER();
');
$archive->buildFromDirectory('dists/Twig/lib');
