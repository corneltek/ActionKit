<?php
namespace ActionKit\ActionTemplate;

interface IActionTemplate
{
    public function register($runner, array $options = array());
    public function generate($targetClassName, $cacheFile, array $options = array());
    public function getTemplateName();
}