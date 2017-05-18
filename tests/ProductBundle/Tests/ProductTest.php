<?php
use ActionKit\ActionRunner;
use ActionKit\ActionRequest;
use ActionKit\Testing\ActionTestCase;
use ActionKit\ServiceContainer;
use ActionKit\ActionTemplate\TwigActionTemplate;
use ActionKit\ActionTemplate\UpdateOrderingRecordActionTemplate;
use ActionKit\Testing\ActionTestAssertions;
use ActionKit\RecordAction\UpdateRecordAction;

use ProductBundle\Model\Product;
use ProductBundle\Model\ProductCollection;
use ProductBundle\Model\ProductImage;
use ProductBundle\Model\ProductImageCollection;
use ProductBundle\Model\ProductSchema;
use ProductBundle\Model\ProductImageSchema;
use ProductBundle\Model\ProductFeatureSchema;
use ProductBundle\Model\ProductCategorySchema;
use ProductBundle\Model\ProductProductSchema;
use ProductBundle\Model\ProductFileSchema;
use ProductBundle\Model\CategorySchema;
use ProductBundle\Model\FeatureSchema;

use ProductBundle\Action\CreateProduct;
use ProductBundle\Action\UpdateProduct;
use ProductBundle\Action\CreateProductFile;
use ProductBundle\Action\CreateProductImage;
use Maghead\Testing\ModelTestCase;

class CustomCreateProductImageAction extends CreateProductImage {

}

function CreateFilesArrayWithAssociateKey(array $files) {
    $array = [ 
        'name' => [],
        'type' => [],
        'tmp_name' => [],
        'saved_path' => [],
        'error' => [],
        'size' => [],
    ];
    foreach ($files as $key => $file) {
        foreach ($array as $field => & $subfields) {
            foreach ($file as $fileField => $fileValue) {
                $array[$field][$key][$fileField] = $fileValue[ $field ];
            }
        }
    }
    return $array;
}

function CreateFileArray($filename, $type, $tmpname) {
    return [
        'name' => $filename,
        'type' => $type,
        'tmp_name' => $tmpname,
        'saved_path' => $tmpname,
        'error' => UPLOAD_ERR_OK,
        'size' => filesize($tmpname),
    ];
}


/**
 * @group maghead
 */
class ProductBundleTest extends ModelTestCase
{
    use ActionTestAssertions;

    public function orderingActionMapProvider() 
    {
        return [
            ['ProductBundle\\Action\\UpdateProductImageOrdering'      , 'ProductBundle\\Model\\ProductImage']      , 
            ['ProductBundle\\Action\\UpdateProductPropertyOrdering'   , 'ProductBundle\\Model\\ProductProperty']   , 
            ['ProductBundle\\Action\\UpdateProductLinkOrdering'       , 'ProductBundle\\Model\\ProductLink']       , 
            ['ProductBundle\\Action\\UpdateProductProductOrdering'    , 'ProductBundle\\Model\\ProductProduct']    , 
            ['ProductBundle\\Action\\UpdateProductSubsectionOrdering' , 'ProductBundle\\Model\\ProductSubsection'] , 
        ];
    }

    public function resizeTypeProvider()
    {
        return [
            ['max_width'],
            ['max_height'],
            ['scale'],
            ['crop_and_scale'],
        ];
    }

    public $driver = 'sqlite';

    public function models()
    {
        return [
            new ProductSchema,
            new ProductCategorySchema,
            new CategorySchema,
            new ProductImageSchema,
            new ProductFeatureSchema,
            new FeatureSchema,
            new ProductProductSchema,
            new ProductFileSchema,
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $records = new ProductCollection;
        $records->delete();

        $records = new ProductImageCollection;
        $records->delete();
    }


    /**
     * @dataProvider orderingActionMapProvider
     */
    public function testProductUpdateOrderingActions($actionClass, $recordClass) 
    {
        $container = new ServiceContainer;
        $generator = $container['generator'];
        $generator->registerTemplate('TwigActionTemplate', new TwigActionTemplate());
        $generator->registerTemplate('UpdateOrderingRecordActionTemplate', new UpdateOrderingRecordActionTemplate());

        $runner = new ActionRunner($container);

        $runner->registerAction('UpdateOrderingRecordActionTemplate', array(
            'namespace' => 'ProductBundle',
            'record_class'     => $recordClass,   // model's name
        ));
        $action = $runner->createAction($actionClass);
        $this->assertNotNull($action);
    }



    /**
     * @expectedException Exception
     */
    public function testMissingSubActionForeignSchem()
    {
        $tmpfile = tempnam('/tmp', 'test_image_');
        copy('tests/data/404.png', $tmpfile);
        $files = [
            'images' => CreateFilesArrayWithAssociateKey([
                'a' => [ 'image' => CreateFileArray('404.png', 'image/png', $tmpfile) ], 
                'b' => [ 'image' => CreateFileArray('404.png', 'image/png', $tmpfile) ], 
            ]),
        ];
        $args = ['name' => 'Test Product', 'images' => [ 
            // files are in another array
            'a' => [ ],
            'b' => [ ],
        ]];

        $request = new ActionRequest($args, $files);
        $create = new CreateProduct($args, [ 'request' => $request ]);

        $relation = clone $create->getRelation('images');
        unset($relation['foreign_schema']);
        $create->addRelation('images', $relation);
        $create->invoke();
    }

    public function testProductCreateWithCustomProductImageSubAction()
    {
        $tmpfile = tempnam('/tmp', 'test_image_');
        copy('tests/data/404.png', $tmpfile);
        $files = [
            'images' => CreateFilesArrayWithAssociateKey([
                'a' => [ 'image' => CreateFileArray('404.png', 'image/png', $tmpfile) ], 
                'b' => [ 'image' => CreateFileArray('404.png', 'image/png', $tmpfile) ], 
            ]),
        ];
        $args = ['name' => 'Test Product', 'images' => [ 
            // files are in another array
            'a' => [ ],
            'b' => [ ],
        ]];

        $request = new ActionRequest($args, $files);
        $create = new CreateProduct($args, [ 'request' => $request]);


        $relation = clone $create->getRelation('images');
        $relation['action'] = 'CustomCreateProductImageAction';

        $create->addRelation('images', $relation);

        $result = $this->assertActionInvokeSuccess($create);

        $product = $create->getRecord();
        $this->assertNotNull($product);
        $this->assertNotNull($product->id, 'product created');

        $images = $product->images;
        $this->assertCount(2, $images);
        foreach($images as $image) { $image->delete(); }
    }

    public function testFetchOneToManyRelationCollection()
    {
        $tmpfile = tempnam('/tmp', 'test_image_');
        copy('tests/data/404.png', $tmpfile);
        $files = [
            'images' => CreateFilesArrayWithAssociateKey([
                'a' => [ 'image' => CreateFileArray('404.png', 'image/png', $tmpfile) ], 
                'b' => [ 'image' => CreateFileArray('404.png', 'image/png', $tmpfile) ], 
            ]),
        ];
        $args = ['name' => 'Test Product', 'images' => [ 
            // files are in another array
            'a' => [ ],
            'b' => [ ],
        ]];
        $request = new ActionRequest($args, $files);
        $create = new CreateProduct($args, [ 'request' => $request ]);
        $result = $this->assertActionInvokeSuccess($create);

        $product = $create->getRecord();
        $this->assertNotNull($product);
        $this->assertNotNull($product->id, 'product created');

        $images = $product->images;
        $this->assertCount(2, $images);

        $images = $create->fetchOneToManyRelationCollection('images');
        $this->assertCount(2, $images);

        foreach($images as $image) { $image->delete(); }
    }


    /**
     * XXX: add test details later
     */
    public function testConvertRecordValidation()
    {
        $ret = ProductImage::create([]);

        $create = new CreateProductImage;
        $create->convertRecordValidation($ret);

        $result = $create->getResult();
        $data = $result->data;
    }

    public function testFetchOneToManyRelationCollectionOnInexistingRelationIdShouldReturnNull()
    {
        $create = new CreateProduct;
        $null = $create->fetchOneToManyRelationCollection('foo');
        $this->assertNull($null);
    }

    public function testFetchManyToManyRelationCollection()
    {
        $args = [];
        $files = [];
        $request = new ActionRequest($args, $files);
        $create = new CreateProduct($args, [ 'request' => $request ]);
        $categories = $create->fetchManyToManyRelationCollection('categories');
        $this->assertInstanceOf('ProductBundle\Model\CategoryCollection', $categories);
    }



    public function testProductCreateWithProductImageSubAction()
    {
        $tmpfile = tempnam('/tmp', 'test_image_');
        copy('tests/data/404.png', $tmpfile);
        $files = [
            'images' => CreateFilesArrayWithAssociateKey([
                'a' => [ 'image' => CreateFileArray('404.png', 'image/png', $tmpfile) ], 
                'b' => [ 'image' => CreateFileArray('404.png', 'image/png', $tmpfile) ], 
            ]),
        ];
        $args = ['name' => 'Test Product', 'images' => [ 
            // files are in another array
            'a' => [ ],
            'b' => [ ],
        ]];
        $request = new ActionRequest($args, $files);
        $create = new CreateProduct($args, [ 'request' => $request ]);
        $result = $this->assertActionInvokeSuccess($create);

        $product = $create->getRecord();
        $this->assertNotNull($product);
        $this->assertNotNull($product->id, 'product created');

        $images = $product->images;
        $this->assertCount(2, $images);
        foreach($images as $image) { $image->delete(); }
    }

    public function testProductCreateSubActionWithCreateProductImage()
    {
        $files = [ ];
        $request = new ActionRequest(['name' => 'Test Product'], $files);
        $ret = Product::create([
            'name' => 'Testing Product',
        ]);
        $this->assertResultSuccess($ret);

        $product = Product::load($ret->key);
        $this->assertNotNull($product->id);
        $create = new CreateProduct(['name' => 'Test Product'], [ 'request' => $request, 'record' => $product ]);
        $createImage = $create->createSubAction('images', [ ]);
        $this->assertNotNull($createImage);
    }

    public function testCreateSubActionWithRelationshipForSubRecordCreate()
    {
        $tmpfile = tempnam('/tmp', 'test_image_');
        copy('tests/data/404.png', $tmpfile);
        $files = [
            'images' => CreateFilesArrayWithAssociateKey([
                'a' => [ 'image' => CreateFileArray('404.png', 'image/png', $tmpfile) ], 
                'b' => [ 'image' => CreateFileArray('404.png', 'image/png', $tmpfile) ], 
            ]),
        ];
        $args = ['name' => 'Test Product', 'images' => [ 
            // files are in another array
            'a' => [ ],
            'b' => [ ],
        ]];

        $request = new ActionRequest($args, $files);
        $createProduct = new CreateProduct($args, [ 'request' => $request ]);

        $relation = clone $createProduct->getRelation('images');
        $relation['create_action'] = 'ProductBundle\Action\CreateProductImage';
        $createProduct->addRelation('images',$relation);
        $this->assertActionInvokeSuccess($createProduct);
    }

    public function testCreateSubActionWithRelationshipAndReloadExistingSubRecord()
    {
        $tmpfile = tempnam('/tmp', 'test_image_') . '.png';
        copy('tests/data/404.png', $tmpfile);
        $files = [
            'image' => CreateFileArray('404.png', 'image/png', $tmpfile),
        ];

        // new ActionRequest(['title' => 'Test Image'], $files);
        $createImage = new CreateProductImage(['title' => 'Test Image'], [ 'files' => $files ]);
        $this->assertActionInvokeSuccess($createImage);
        $image = $createImage->getRecord();
        $this->assertNotNull($image);
        $this->assertNotNull($image->id);


        $ret = Product::create([ 'name' => 'Test Product' ]);
        $product = Product::load($ret->key);

        $updateProduct = new UpdateProduct(['name' => 'Updated Product'], [ 'record' => $product ]);

        $relation = clone $updateProduct->getRelation('images');

        $updateImage = $updateProduct->createSubActionWithRelationship($relation, [ 'id' => $image->id ], $files);
        $this->assertInstanceOf(UpdateRecordAction::class, $updateImage);
        $this->assertActionInvokeSuccess($updateImage);

        $relation = clone $updateProduct->getRelation('images');
        $relation['update_action'] = 'ProductBundle\Action\UpdateProductImage';
        $updateImage = $updateProduct->createSubActionWithRelationship($relation, [ 'id' => $image->id ], $files);
        $this->assertInstanceOf(UpdateRecordAction::class, $updateImage);
        $this->assertActionInvokeSuccess($updateImage);
    }




    /**
     * @requires extension gd
     */
    public function testCreateProductImageWithActionRequest()
    {
        $tmpfile = tempnam('/tmp', 'test_image_') . '.png';
        copy('tests/data/404.png', $tmpfile);
        $files = [
            'image' => CreateFileArray('404.png', 'image/png', $tmpfile),
        ];

        $request = new ActionRequest(['title' => 'Test Image'], $files);
        $create = new CreateProductImage(['title' => 'Test Image'], [ 'request' => $request ]);
        $ret = $create->invoke();
        $this->assertTrue($ret);
        $this->assertInstanceOf('ActionKit\Result', $create->getResult());
    }

    /**
     * @dataProvider resizeTypeProvider
     * @requires extension gd
     */
    public function testCreateProductImageWithAutoResize($resizeType)
    {
        $tmpfile = tempnam('/tmp', 'test_image_') . '.png';
        copy('tests/data/404.png', $tmpfile);
        $files = [
            'image' => CreateFileArray('404.png', 'image/png', $tmpfile),
        ];

        // new ActionRequest(['title' => 'Test Image'], $files);
        $create = new CreateProductImage([
            'title' => 'Test Image',
            'image_autoresize' => $resizeType,
        ], [ 'files' => $files ]);
        $ret = $create->invoke();
        $this->assertTrue($ret);
        $this->assertInstanceOf('ActionKit\Result', $create->getResult());
    }

    public function testCreateProductImageWithFilesArray()
    {
        $tmpfile = tempnam('/tmp', 'test_image_') . '.png';
        copy('tests/data/404.png', $tmpfile);
        $files = [
            'image' => CreateFileArray('404.png', 'image/png', $tmpfile),
        ];

        // new ActionRequest(['title' => 'Test Image'], $files);
        $create = new CreateProductImage(['title' => 'Test Image'], [ 'files' => $files ]);
        $this->assertActionInvokeSuccess($create);
    }



    /**
     * @expectedException Exception
     */
    public function testCreateSubActionWithUndefinedRelation()
    {
        $files = [ 'image' => [] ];
        // new ActionRequest(['title' => 'Test Image'], $files);
        $create = new CreateProductImage([], [ 'files' => $files ]);
        $create->createSubAction('foo', [], $files);
    }

    public function testCreateProductImageWithRequiredField()
    {
        $files = [ 'image' => [] ];
        // new ActionRequest(['title' => 'Test Image'], $files);
        $create = new CreateProductImage([], [ 'files' => $files ]);
        $this->assertActionInvokeFail($create);
    }

    public function testCreateProductImageWithRequiredField2()
    {
        $files = [];
        // new ActionRequest(['title' => 'Test Image'], $files);
        $create = new CreateProductImage([], [ 'files' => $files ]);
        $this->assertActionInvokeFail($create);
    }


    public function testCreateProductFileWithFilesArray()
    {
        $tmpfile = tempnam('/tmp', 'test_image_');
        copy('tests/data/404.png', $tmpfile);
        $files = [
            'file' => CreateFileArray('404.png', 'image/png', $tmpfile),
        ];
        $create = new CreateProductFile([ ], [ 'files' => $files ]);
        $ret = $create->invoke();
        $this->assertTrue($ret);
        $this->assertInstanceOf('ActionKit\Result', $create->getResult());
    }


}



