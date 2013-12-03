<?php
namespace ActionKit\View;
use FormKit;
use FormKit\Element;
use FormKit\Widget\Label;
use FormKit\Widget\HiddenInput;
use FormKit\Widget\ButtonInput;
use FormKit\Widget\SubmitInput;
use FormKit\Widget\CheckboxInput;
use FormKit\Layout\GenericLayout;

/**
 * Render many-to-many relationship records as a checkbox list.
 */
class ManyToManyCheckboxView
{


    public function _createCheckedMap($subset) 
    {
        $checked = array();
        foreach( $subset as $item ) {
            $checked[ $item->id ] = $item;
        }
        return $checked;
    }


    /**
     * Render list item
     *
     * @param string $relationId   The relationship identity
     * @param BaseModel $item      Record object.
     * @return Element('li')
     */
    public function renderItem($relationId, $item, $on = false) 
    {
        $id = $item->id;
        $li       = new Element('li');
        $label    = new Label;
        $hiddenId = new HiddenInput("{$relationId}[{$id}][_foreign_id]", array( 'value' => $id ) );
        $checkboxValue = $on ? 1 : 0;
        $checkbox = new CheckboxInput("{$relationId}[{$id}][_connect]",array( 
            'value' => $checkboxValue,
        ));
        if ( $on ) {
            $checkbox->check();
        }
        $label->append( $checkbox );
        $label->appendText( $item->dataLabel() );
        $label->append( $hiddenId );
        $li->append($label);
        return $li;
    }

    /**
     * Render a checkbox list base on the subset and superset collections.
     *
     * @param string $relationId the relationship id, used to render the form field key.
     * @param BaseCollection[]   the related collection. (optional)
     * @param BaseCollection[]   the superset collection.
     */
    public function renderList($relationId, $subset, $superset) 
    {
        $ul = new Element('ul');
        $ul->addClass('actionkit-checkbox-view');

        $checked = $subset ? $this->_createCheckedMap($subset) : array();

        // now we should render the superset, and set the checkbox to be 
        // connected from the $checked array.
        foreach( $superset as $item ) {
            $li = $this->renderItem( $relationId, $item, isset($checked[$item->id]) );
            $li->appendTo($ul);
        }
        return $ul;
    }



    /**
     * @param string                    $relationId the relationship id of the record.
     * @param LazyRecord\BaseModel      $record     the record object.
     * @param LazyRecord\BaseCollection $collection the colletion object.
     */
    public function render($relationId, $record, $collection)
    {
        return $this->renderList( $relationId,
            ($record->id ? $record->{$relationId} : null),
            $collection);
    }
}


