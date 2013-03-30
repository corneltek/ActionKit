<?php
namespace ActionKit\View;

/**
 * Render many-to-many relationship records as a checkbox list.
 */
class ManyToManyCheckboxView
{
    public function render($relationId, $record, $collection)
    {
        /**
         * $record: the main record
         * relationId: the relationship id
         * collection: collection records
         */
        $ul = new Element('ul');
        $connected = array();

        if ( $record->id && isset($record->{ $relationId }) ) {
            // so that we can get product_id field since we joined the table.
            $foreignRecords = $record->{ $relationId };
            foreach ( $foreignRecords as $fRecord ) {
                $fId = $fRecord->id;
                $li       = new Element('li');
                $label    = new Label;
                $hiddenId = new HiddenInput(   "{$relationId}[{$fId}][id]", array( 'value' => $fId ) );
                $checkbox = new CheckboxInput( "{$relationId}[{$fId}][_connect]",array( 
                    'boolean_value' => false,
                    'value' => 1,
                ));
                $checkbox->check();
                $label->append( $checkbox );
                $label->appendText( $fRecord->dataLabel() );
                $label->append( $hiddenId );
                $li->append($label)->appendTo($ul);
                $connected[ $fId ] = $fRecord;
            }
        }

        foreach( $collection as $record ) {
            if ( isset($connected[$record->id]) ) {
                continue;
            }
            $li = new Element('li');
            $label = new Label;
            $hiddenId = new HiddenInput(   "{$relationId}[{$record->id}][id]", array( 'value' => $record->id ) );
            $checkbox = new CheckboxInput( "{$relationId}[{$record->id}][_connect]",array(
                'boolean_value' => false,
                'value' => 1,
            ));
            $label->append($checkbox);
            $label->appendText($record->dataLabel());
            $label->append( $hiddenId );
            $li->append($label)->appendTo($ul);
        }
        return $ul;
    }
}


