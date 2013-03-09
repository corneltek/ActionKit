<?php
namespace ActionKit\FieldView;
use FormKit;
use FormKit\Element\Div;
use FormKit\Element\Span;
use FormKit\Widget\HiddenInput;

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
        $labelDiv = new Div(array( 'class' => $this->labelClass ));
        $inputDiv = new Div(array( 'class' => $this->inputClass ));

        $widget = $this->column->createWidget(null, $this->widgetAttributes);
        $inputDiv->append( $widget );

        if (! $widget instanceof HiddenInput) {
            $label = $this->column->createLabelWidget();
            $labelDiv->append( $label );
            $wrapper->append($labelDiv);
        }

        $wrapper->append($inputDiv);

        if ($this->column->hint) {
            $hintEl  = new Span(array( 'class' => $this->hintClass ));
            $hintEl->append( $this->column->hint );
            $wrapper->append($hintEl);
        }

        return $wrapper;
    }

    public function render()
    {
        return $this->build()->render();
    }
}
