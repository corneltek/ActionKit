#!/bin/bash
# find plugins core app lib -iname "*.php" -exec xgettext --from-code=UTF-8 -n --omit-header -L PHP -j -o locale/phifty.pot {} \;
find plugins tests dists/EteDB cache core lib -iname "*.php" -exec xgettext --from-code=UTF-8 -n -L PHP -j -o locale/phifty.pot {} \;
