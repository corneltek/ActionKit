<?php


namespace ActionKit\FieldView;

use ActionKit\Param\Param;

class BootstrapFieldViewTest extends \PHPUnit\Framework\TestCase
{
    public function testText()
    {
        $column = new Param('account', new \ActionKit\Action);
        $column->required(1);

        $field = new BootstrapFieldView($column, array(
          'labelClass' => 'col-lg-2',
          'inputWrapperClass' => 'col-lg-10'
        ));
        $field->setWidgetAttributes(array(
          'placeholder' => "ariel123",
          'readoly' => "",
          'autocomplete' => "off",
        ));
        $html = $field->render();

        $xml = simplexml_load_string($html);
        
        $this->assertEquals('form-group formkit-widget-textinput', (string)$xml->attributes()['class']);
        $label = $xml->label;
        $this->assertEquals('col-lg-2', (string)$label->attributes()['class']);
        $this->assertEquals('* Account', (string)$label[0]);

        $div = $xml->div;
        $this->assertEquals('col-lg-10', (string)$div->attributes()['class']);
        $input = $div->input->attributes();
        $this->assertEquals('formkit-widget formkit-widget-text form-control', $input->class);
        $this->assertEquals('account', $input->name);
        $this->assertEquals('ariel123', $input->placeholder);
        $this->assertEquals('off', $input->autocomplete);
    }
}

/*

<div class="form-group formkit-widget-textinput">
  <label class="col-lg-2 control-label">Account</label>
  <div class="col-lg-10">
    <input class="form-control" name="account" type="text" label="Account" placeholder="ariel123" autocomplete="off"/>
  </div>
</div>
*/

    
