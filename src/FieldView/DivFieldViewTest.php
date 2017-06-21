<?php

namespace ActionKit\FieldView;

use ActionKit\Param\Param;
use ActionKit\Action;

class DivFieldViewTest extends \PHPUnit\Framework\TestCase
{
    public function testText()
    {
        $column = new Param('name', new Action);
        $column->required(1);
        $column->default('John');

        $field = new DivFieldView($column);
        $html = $field->render();

        $xml = simplexml_load_string($html);
        $this->assertEquals('v-field formkit-widget-textinput', (string)$xml->attributes()['class']);
        $div = $xml->div[0];
        $this->assertEquals('label', (string)$div->attributes()['class']);
        $this->assertEquals('* Name', (string)$div->label);

        $label = $div->label;
        $this->assertEquals('formkit-widget formkit-label formkit-widget-label', (string)$label->attributes()['class']);

        $div = $xml->div[1];
        $this->assertEquals('input', (string)$div->attributes()['class']);
        $input = $div->input->attributes();
        $this->assertEquals('formkit-widget formkit-widget-text', $input->class);
        $this->assertEquals('name', $input->name);
        $this->assertEquals('text', $input->type);
        $this->assertEquals('John', $input->value);
    }
}

/*
<div class="v-field formkit-widget-textinput">
  <div class="label">
    <label class="formkit-widget formkit-label formkit-widget-label">Name</label>
  </div>
  <div class="input">
    <input class="formkit-widget formkit-widget-text" name="name" type="text" label="Name" value="John"/>
  </div>
</div>
*/
    
