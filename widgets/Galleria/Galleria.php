<?php

namespace Phifty\Widgets;

use Phifty\Widget;

class Galleria extends Widget
{
	public $width = 400;
	public $height = 400;
	public $images = array();

    function js() 
    {
        return array( "galleria-1.2.5.js" );
    }

    function css() 
    {
        return array( "themes/twelve/galleria.twelve.css" );
    }

	function addImage( $path , $attrs = array() ) 
	{
		$this->images[] = array( 'path' => $path, 'attrs' => $attrs );
	}

    function template()
    {
		return <<<"HTML"

<script type="text/javascript">
jQuery(document.body).ready(function() {
	Galleria.loadTheme('{{ Widget.baseUrl }}/themes/twelve/galleria.twelve.min.js');
	jQuery('#gallery').height( 500 );
	jQuery('#gallery').galleria();
});
</script>

<div id="gallery">
{% for image in Widget.images %}
	<img src="{{ image.path }}"/>
{% endfor %}
</div>

HTML;

    }
}


