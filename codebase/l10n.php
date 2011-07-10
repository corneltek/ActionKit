<?php

{
    $l10n_obj = l10n();
    $langs = $i18n_c->val('supports');
    foreach( $langs as $ln ) {
        $l10n_obj->add( $ln );
    }
    $l10n_obj->deflang( $default_lang );
    $l10n_obj->domain( 'website' )
        ->localedir( APPDIR . $i18n_c->val('localedir') )
        ->init();
}

?>
