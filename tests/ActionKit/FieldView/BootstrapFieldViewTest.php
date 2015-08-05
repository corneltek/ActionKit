<?php
use ActionKit\Param\Param;
use ActionKit\FieldView\BootstrapFieldView;

class BootstrapFieldViewTest extends PHPUnit_Framework_TestCase
{
    public function testText()
    {
        $column = new Param('account');
        $column->required(1);

        $field = new BootstrapFieldView($column, array(
          'labelClass' => 'col-lg-2',
          'inputWrapperClass' => 'col-lg-10'
        ));
        $field->setWidgetAttributes(array(
          'placeholder' => "ariel123",
          'readoly' => "",
          'autocomplete' => "off",
          'class' => "form-control"
        ));
        $html = $field->render();

        $xml = simplexml_load_string($html);
        
        is('form-group formkit-widget-textinput', (string)$xml->attributes()['class']);
        $label = $xml->label;
        is('col-lg-2', (string)$label->attributes()['class']);
        is('Account', (string)$label[0]);

        $div = $xml->div;
        is('col-lg-10', (string)$div->attributes()['class']);
        $input = $div->input->attributes();
        is('form-control', $input->class);
        is('account', $input->name);
        is('ariel123', $input->placeholder);
        is('off', $input->autocomplete);
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

    