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
 *
 *
 Usage in twig template:

    {% set subview = CRUD.Action.asView('ActionKit\\View\\StackView',{ "no_form": 1 }) %}

    {{ subview.buildRelationalActionViewForExistingRecords('categories').render() |raw }}

    {{ subview.buildManyToManyRelationalActionViewForExistingRecords(CRUD.Record, 'categories').render() |raw }}

    {{ subview.renderManyToManyEditor(CRUD.Record,'categories', categories) |raw}}

  Or you can use it directly:

    $view = new \ActionKit\View\ManyToManyCheckboxView;
    echo $view->render($relationId, $record, $superset);

 */
class ManyToManyCheckboxView
{

    public $checked = array();

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
     * @param Model $item      Record object.
     * @return Element('li')
     */
    public function renderItem($relationId, $subset, $item, $on = false) 
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
        $label->append( $item->dataLabel() );
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

        // now we should render the superset, and set the checkbox to be 
        // connected from the $this->checked array.
        foreach( $superset as $item ) {
            $li = $this->renderItem( $relationId, $subset, $item, isset($this->checked[$item->id]) );
            $li->appendTo($ul);
        }
        return $ul;
    }



    /**
     * @param string                    $relationId the relationship id of the record.
     * @param Maghead\Runtime\Model      $record     the record object.
     * @param Maghead\Runtime\Collection $subset     the subset colletion object.
     * @param Maghead\Runtime\Collection $collection the superset colletion object.
     */
    public function render($relationId, $record, $subset = null, $collection = null)
    {
        if ( ! $subset ) {
            $subset = ($record->id ? $record->{$relationId} : null);
        }
        if ( ! $collection  ) {
            $collection = $record->fetchManyToManyRelationCollection($relationId);
        }
        $this->checked = $subset ? $this->_createCheckedMap($subset) : array();
        return $this->renderList( $relationId,
            $subset,
            $collection);
    }
}


