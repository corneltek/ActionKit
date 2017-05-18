<?php
namespace ActionKit\RecordAction;

use Exception;
use ActionKit\Utils;

function duplicate_file($from)
{
    $to = Utils::filename_increase_suffix_number($from);
    copy($from, $to);
    return $to;
}

/**
 * Duplicate data based on the schema
 */
function duplicate_data($data, $schema)
{
    $newData = [];
    foreach ($data as $name => $val) {
        if ($val == null) {
            continue;
        }
        $column = $schema->getColumn($name);

        switch ($column->contentType) {
        case "File":
        case "ImageFile":
            $newData[ $name ] = duplicate_file($val);
            break;
        default:
            $newData[ $name ] = $val;
        }
    }
    if ($pk = $schema->primaryKey) {
        unset($newData[$pk]);
    }
    return $newData;
}


class BulkCopyRecordAction extends BulkRecordAction
{
    const TYPE = 'bulk_copy';


    /**
     * @var array New fields are defined to be override.
     */
    public $newFields = array('lang');

    /**
     * @var array unsetFields are defined to reset.
     */
    public $unsetFields = array();

    public function schema()
    {
        foreach ($this->newFields as $field) {
            // XXX: initialize the label from schema
            $this->param($field);
        }
        parent::schema();
    }


    public function prepareData($data)
    {
        if (! empty($this->unsetFields)) {
            foreach ($this->unsetFields as $field) {
                unset($data[$field]);
            }
        }

        if (! empty($this->newFields)) {
            foreach ($this->newFields as $field) {
                if ($newValue = $this->arg($field)) {
                    $data[$field] = $newValue;
                } else {
                    unset($data[$field]);
                }
            }
        }
        return $data;
    }

    public function beforeCopy($record, $data)
    {
    }

    public function afterCopy($record, $data, $newRecord)
    {
    }

    public function finalize($records, $newRecords)
    {
    }

    public function run()
    {
        $newRecord = new $this->recordClass;
        $schema = $newRecord->getSchema();

        $newRecords = array();
        $records = $this->loadRecords();
        foreach ($records as $record) {
            $newData = $record->getData();
            $newData = $this->prepareData($newData);

            $this->beforeCopy($record, $newData);

            try {
                $newRecord = $this->duplicateRecord($record, $schema, $newData);
                if ($result = $this->afterCopy($record, $newData, $newRecord)) {
                    return $result;
                }
                $newRecords[] = $newRecord;
            } catch (Exception $e) {
                return $this->error($e->message);
            }
        }
        $this->finalize($records, $newRecords);
        return $this->success(count($newRecords) . ' 個項目複製成功。');
    }

    public function duplicateRecord($record, $schema, $data = null)
    {
        $data = $data ?: $record->getData();
        $newData = duplicate_data($data, $schema);

        $newRecord = $schema->newModel();
        $ret = $newRecord->create($newData);
        if (! $ret->success) {
            throw new Exception($ret->message);
        }
        // XXX: check error

        $relations = $schema->getRelations();
        foreach ($relations as $rId => $relation) {
            if (! $relation->isHasMany()) {
                continue;
            }

            // var_dump( $relation );

            $foreignRecord = $relation->newForeignModel();
            $foreignColumnName = $relation['foreign_column'];

            // fetch the related records
            if (isset($record->{ $rId })) {
                $relatedRecords = $record->{ $rId };
                $relatedRecords->fetch();

                foreach ($relatedRecords as $relatedRecord) {
                    $relatedRecordData = duplicate_data($relatedRecord->getData(), $relatedRecord->getSchema());
                    $relatedRecordData[ $foreignColumnName ] = $newRecord->id; // override the foreign column to the new record primary key
                    $ret = $foreignRecord->create($relatedRecordData);
                    if (! $ret->success) {
                        throw new Exception($ret->message);
                    }
                    // XXX: check error
                }
            }
        }
        return $newRecord;
    }
}
