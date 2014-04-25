<?php
namespace ProductBundle\Model;
use Phifty\Model;
use Phifty\FileUtils;

class CategoryFile extends Model
{
    public function schema($schema)
    {
        $schema->column('category_id')
            ->integer()
            ->refer('ProductBundle\\Model\\Category')
            ->renderAs('SelectInput')
            ->label('類別');

        $schema->column('title')
            ->varchar(130)
            ->label('檔案標題');

        $schema->column('mimetype')
            ->varchar(16)
            ->label('檔案格式')
            ;

        $schema->column('file')
            ->varchar(130)
            ->required()
            ->label('檔案')
            ;
    }


    public function beforeUpdate($args)
    {
        if( isset($args['file']) )
            $args['mimetype'] = FileUtils::mimetype($args['file']);
        return $args;
    }

    public function beforeCreate($args)
    {
        if( isset($args['file']) )
            $args['mimetype'] = FileUtils::mimetype($args['file']);
        return $args;
    }

    public function updateMimetype()
    {
        if( file_exists($this->file) ) {
            $mimetype = FileUtils::mimetype($this->file);
            if($mimetype) {
                $this->update(array('mimetype' => $mimetype));
            }
        }
    }


#boundary start 8ed945e5afdf5aa65571ce21ab189942
	const schema_proxy_class = 'ProductBundle\\Model\\CategoryFileSchemaProxy';
	const collection_class = 'ProductBundle\\Model\\CategoryFileCollection';
	const model_class = 'ProductBundle\\Model\\CategoryFile';
	const table = 'category_files';
#boundary end 8ed945e5afdf5aa65571ce21ab189942
}

