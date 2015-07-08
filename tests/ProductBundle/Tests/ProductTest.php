<?php
use ActionKit\ActionRunner;
use ActionKit\ActionRequest;
use ActionKit\ServiceContainer;
use ActionKit\ActionTemplate\TwigActionTemplate;
use ActionKit\ActionTemplate\UpdateOrderingRecordActionTemplate;
use ProductBundle\Model\Product;
use ProductBundle\Action\CreateProduct;
use ProductBundle\Action\CreateProductFile;
use ProductBundle\Action\CreateProductImage;


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


class ProductBundleTest extends PHPUnit_Framework_TestCase
{
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


    public function testProductSubActionWithCreateProductImage()
    {
        $files = [ ];
        $request = new ActionRequest(['name' => 'Test Product'], $files);
        $product = new Product;
        $product->create([
            'name' => 'Testing Product',
        ]);
        $this->assertNotNull($product->id);

        $create = new CreateProduct(['name' => 'Test Product'], [ 'request' => $request, 'record' => $product, ]);

        $relation = $create->getRelation('images');
        $create->createSubAction($relation, [ ]);
    }



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
        $ret = $create->invoke();
        $this->assertTrue($ret);
        $this->assertInstanceOf('ActionKit\Result', $create->getResult());
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



