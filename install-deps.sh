#!/bin/bash
pear channel-discover pear.corneltek.com
pear channel-discover components.ez.no
pear channel-discover pear.symfony-project.com
pear channel-discover pear.pearplex.net
pear channel-discover pear.phpunit.de
pear channel-discover pear.twig-project.org
pear channel-discover pear.firephp.org
pear channel-discover pear.symfony.com/Finder-2.0.12
pear install -f -a phpunit/PHPUnit
pear install -f -a twig/Twig
pear install -f -a pearplex/PHPExcel
pear install -f -a firephp/FirePHPCore
pear install -f -a symfony2/Finder
pecl install apc
pecl install yaml
pecl install xdebug
