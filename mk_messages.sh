#!/bin/bash
# find plugins core app lib -iname "*.php" -exec xgettext --from-code=UTF-8 -n --omit-header -L PHP -j -o locale/phifty.pot {} \;
find plugins tests dists/EteDB cache core lib -iname "*.php" -exec xgettext --from-code=UTF-8 -n -L PHP -j -o locale/phifty.pot {} \;


f=locale/zh_TW/LC_MESSAGES/phifty.po
msgmerge $f locale/phifty.pot >| tmp.po
cp tmp.po $f
open -W $f
po update
