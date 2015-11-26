<?php
namespace ActionKit\RecordAction;

abstract class DeleteRecordAction
    extends BaseRecordAction
{
    const TYPE = 'delete';

    public $enableLoadRecord = true;
    public $unlink= true;

    public function run()
    {
        /* default run method , to run create action */
        $record = $this->record;
        $schema = $record->getSchema();
        $data = $record->getStashedData();
        foreach( $data as $name => $val ) {
            if ( $val == null ) {
                continue;
            }
            $column = $schema->getColumn( $name );
            switch( $column->contentType ) {
                case "ImageFile":
                case "File":
                    if ( $this->unlink && file_exists($val) ) {
                        unlink($val);
                    }
                    break;
            }
        }

        $relations = $schema->getRelations();
        foreach( $relations as $rId => $relation ) {
            if ( ! $relation->isHasMany() ) {
                continue;
            }
            $relatedRecords = $record->{ $rId };
            $relatedRecords->fetch();
            foreach($relatedRecords as $rr) {
                $rr->delete();
            }
        }

        return $this->doDelete( $this->args );
    }

    public function doDelete($args)
    {
        $ret = $this->record->delete();
        if ($ret->success) {
            return $this->deleteSuccess($ret);
        } else {
            return $this->deleteError($ret);
        }
    }

    /**
     * @inherit
     */
    public function runValidate()
    {
        if (isset($this->args['id'])) {
            return true;
        }
        return false;
    }

    public function successMessage($ret)
    {
        return $this->messagePool->translate('record_action.successful_delete', $this->record->getLabel());
    }

    public function errorMessage($ret)
    {
        return $this->messagePool->translate('record_action.failed_delete', $this->record->getLabel());
    }

    public function deleteSuccess($ret)
    {
        return $this->success($this->successMessage($ret), array( 'id' => $this->record->id) );
    }

    public function deleteError($ret)
    {
        return $this->error($this->errorMessage($ret));
    }

}
