<?php
namespace CascadingCache;

interface CascadingCache_Interface 
{
	function get($key);
	function set($key);
	function truncate();
	function remove();
}

