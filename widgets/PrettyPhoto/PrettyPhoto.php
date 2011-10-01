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
<!--
<ul class="prettyphoto clearfix">
    <li><a href="{{ Widget.baseUrl }}/images/fullscreen/1.jpg?lol=lol" rel="prettyPhoto[gallery1]" title="Caption"><img src="{{ Widget.baseUrl }}/images/thumbnails/t_1.jpg" width="60" height="60" alt="Red round shape" /></a></li>
    <li><a href="{{ Widget.baseUrl }}/images/fullscreen/2.jpg" rel="prettyPhoto[gallery1]"><img src="{{ Widget.baseUrl }}/images/thumbnails/t_2.jpg" width="60" height="60" alt="Nice building" /></a></li>
    <li><a href="{{ Widget.baseUrl }}/images/fullscreen/3.jpg" rel="prettyPhoto[gallery1]"><img src="{{ Widget.baseUrl }}/images/thumbnails/t_3.jpg" width="60" height="60" alt="Fire!" /></a></li>
    <li><a href="{{ Widget.baseUrl }}/images/fullscreen/4.jpg" rel="prettyPhoto[gallery1]"><img src="{{ Widget.baseUrl }}/images/thumbnails/t_4.jpg" width="60" height="60" alt="Rock climbing" /></a></li>
    <li><a href="{{ Widget.baseUrl }}/images/fullscreen/5.jpg" rel="prettyPhoto[gallery1]"><img src="{{ Widget.baseUrl }}/images/thumbnails/t_5.jpg" width="60" height="60" alt="Fly kite, fly!" /></a></li>
    <li><a href="{{ Widget.baseUrl }}/images/fullscreen/6.jpg" rel="prettyPhoto[gallery1]"><img src="{{ Widget.baseUrl }}/images/thumbnails/t_2.jpg" width="60" height="60" alt="Nice building" /></a></li>
</ul>
-->

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
