<?php
namespace ActionKit\FieldView;
use FormKit;
use FormKit\Element\Div;
use FormKit\Element\Span;
use FormKit\Widget\HiddenInput;
use FormKit\Widget\CheckboxInput;

class DivFieldView
{

    /**
     * @var ActionKit\Param object
     */
    public $column;

    public $wrapperClass = 'v-field';

    public $labelClass = 'label';

    public $inputClass = 'input';

    public $hintClass = 'hint';

    public $container;

    public $widgetAttributes = array();

    public function __construct($column, $options = array())
    {
        $this->column = $column;
    }

    public function setWidgetAttributes($attrs)
    {
        $this->widgetAttributes = $attrs;
    }

    /**
     *
     * Build a div structure like this:

            <div class="v-field">
                <div class="label">{% trans 'Role' %}</div>
                <div class="input">
                    <select name="role">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
     */
    public function build()
    {
        $wrapper = new Div(array(
            'class' => $this->wrapperClass,
        ));


        $widget = $this->column->createWidget(null, $this->widgetAttributes);
        $wrapper->addClass($widget->convertClassToCssClassName());

        if ( $widget instanceof CheckboxInput ) {
            $label = $this->column->createLabelWidget();
            $label->prepend($widget);
            $wrapper->append($label);
        } elseif ( $widget instanceof HiddenInput) {
            $wrapper->append( $widget );
        } else {
            $labelDiv = new Div(array( 'class' => $this->labelClass ));
            $inputDiv = new Div(array( 'class' => $this->inputClass ));
            $inputDiv->append( $widget );
            $label = $this->column->createLabelWidget();
            $labelDiv->append( $label );
            $wrapper->append($labelDiv);
            $wrapper->append($inputDiv);
            if ($this->column->hint) {
                $hintEl  = new Span(array( 'class' => $this->hintClass ));
                $hintEl->append( $this->column->hint );
                $wrapper->append($hintEl);
            }
        }
        return $wrapper;
    }

    public function render()
    {
        return $this->build()->render();
    }
}
