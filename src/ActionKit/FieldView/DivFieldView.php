<?php
namespace ActionKit\FieldView;
use FormKit;

class DivFieldView
{

    /**
     * @var ActionKit\Param object
     */
    public $column;

    public $wrapperClass = 'v-field';

    public $labelClass = 'label';

    public $inputClass = 'input';

    public $container;

    public $widgetAttributes = array();

    public function __construct($column, $options = array()) 
    {
        $this->column = $column;
    }

    public function setWidgetAttributes($attrs) {
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
        $wrapper = new FormKit\Element\Div(array( 
            'class' => $this->wrapperClass,
        ));
        $labelDiv = new FormKit\Element\Div(array( 'class' => $this->labelClass ));
        $inputDiv = new FormKit\Element\Div(array( 'class' => $this->inputClass ));

        $label = $this->column->createLabelWidget();
        $widget = $this->column->createWidget(null, $this->widgetAttributes);

        $labelDiv->append( $label );
        $inputDiv->append( $widget );

        $wrapper->append($labelDiv);
        $wrapper->append($inputDiv);
        return $wrapper;
    }

    public function render()
    {
        return $this->build()->render();
    }
}

