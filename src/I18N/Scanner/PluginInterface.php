<?php
namespace I18N\Scanner;

interface PluginInterface { 
    public function scan( $utf8Content, $scanner );
}

