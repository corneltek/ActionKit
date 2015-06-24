<?php
namespace ActionKit\ActionTemplate;
use ActionKit\ActionRunner;

interface ActionTemplate
{
    public function register(ActionRunner $runner, array $options = array());
    public function generate($targetClassName, $cacheFile, array $options = array());
    public function getTemplateName();
}