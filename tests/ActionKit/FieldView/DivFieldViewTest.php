<?php
use ActionKit\Param\Param;
use ActionKit\FieldView\DivFieldView;

class DivFieldViewTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $column = new Param('name');
        $column->required(1);
        $column->default('John');

        $field = new DivFieldView($column);
        $html = $field->render();

        $xml = simplexml_load_string($html);
        is('v-field formkit-widget-textinput', (string)$xml->attributes()['class']);
        $div = $xml->div[0];
        is('label', (string)$div->attributes()['class']);
        is('Name', (string)$div->label);

        $label = $div->label;
        is('formkit-widget formkit-label formkit-widget-label', (string)$label->attributes()['class']);

        $div = $xml->div[1];
        is('input', (string)$div->attributes()['class']);
        $input = $div->input->attributes();
        is('formkit-widget formkit-widget-text', $input->class);
        is('name', $input->name);
        is('text', $input->type);
        is('John', $input->value);
    }
}
   