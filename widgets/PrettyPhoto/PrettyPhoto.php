<?php

namespace Phifty\Widgets;
use Phifty\Widget;

class PrettyPhoto extends Widget
{
    public $images;
    public $config = array( 
        'animation_speed' => 'normal',
        'theme' => 'light_rounded',
        'slideshow' => 3000, 
        'autoplay_slideshow' => true
    );

    function config($config)
    {
        $this->config = $config;
    }

    function addImage($image) 
    {
        $this->images[] = $image;
    }

	function js()
	{
		return array('js/jquery.prettyPhoto.js');
	}

	function css()
	{
		return array('css/prettyPhoto.css');
	}

	function template()
	{
		return <<<"HTML"
<style>
ul.prettyphoto li { 
    display: inline;
    list-style: none;
}
</style>
<ul class="prettyphoto">
    {% for item in Widget.images %}
        <li>
            <a href="{{ item.image }}" 
                rel="{{ item.rel | default('prettyPhoto[gallery]') }}"
                {% if item.caption %}  title="{{ item.caption }}"  {% endif %}
                ><img src="{{ item.thumb | default(item.image) }}" 
                    {% if item.width %}   width="{{ item.width }}"   {% endif %}
                    {% if item.height %}  height="{{ item.height }}" {% endif %}
                    {% if item.alt %}     alt="{{ item.alt }}"       {% endif %}
            /></a>
        </li>
    {% endfor %}
</ul>
<script type="text/javascript">
jQuery(".prettyphoto a[rel^='prettyPhoto']").prettyPhoto({
    animation_speed:'normal',
    theme:'light_rounded',
    slideshow:3000, 
    social_tools: false,
    autoplay_slideshow: false,
    autoplay: false,
    allow_resize: false
});
</script>

HTML;
	}


}
