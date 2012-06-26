<?php
namespace I18N\Scanner;

class GettextPlugin
    implements PluginInterface
{
    public function scan($utf8Content,$scanner) {

        if( preg_match_all('#__?\(\s*(["\'])(.*?)\1\)#sm',$utf8Content,$regs) ) {
            var_dump( $regs ); 
        }

    }
}


