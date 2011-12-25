<?php

namespace Phifty\Assets;

use Phifty\Asset\Asset;

class Galleria extends Asset
{
	public $width = 400;
	public $height = 400;
	public $images = array();

    function height( $px )
    {
        $this->height = $px;
    }

    function width( $px )
    {
        $this->width = $px;
    }

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
		Galleria.loadTheme('{{ Asset.baseUrl }}/themes/twelve/galleria.twelve.min.js');
		jQuery('#gallery').height( {{ Asset.height }} );
		jQuery('#gallery').galleria();   
	}
	);
	</script>

	<div id="gallery">
	{% for image in Asset.images %}
		<img src="{{ image.path }}"/>
	{% endfor %}
	</div>

HTML;

    }
}


