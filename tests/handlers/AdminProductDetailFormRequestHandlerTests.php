<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminProductDetailFormRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    ColorsFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SizesFixture,
    SubcategoryFixture};
use app\models\ProductsModel;
use app\forms\{AbstractBaseForm,
    AdminProductForm};

/**
 * Тестирует класс AdminProductDetailFormRequestHandler
 */
class AdminProductDetailFormRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'colors'=>ColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'brands'=>BrandsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminProductDetailFormRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminProductDetailFormRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductDetailFormRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminProductDetailFormRequestHandler::adminProductDetailFormWidgetConfig
     */
    public function testAdminProductDetailFormWidgetConfig()
    {
        $productsModel = new class() extends ProductsModel {};
        $categoriesArray = [new class() {
            public $id = 1;
            public $name = 'category';
        }];
        $subcategoryArray = [new class() {
            public $id = 1;
            public $name = 'subcategory';
        }];
        $colorsArray = [new class() {
            public $id = 1;
            public $color = 'color';
        }];
        $sizesArray = [new class() {
            public $id = 1;
            public $size = 'size';
        }];
        $brandsArray = [new class() {
            public $id = 1;
            public $brand = 'brand';
        }];
        $adminProductForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminProductDetailFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $productsModel, $categoriesArray, $subcategoryArray, $colorsArray, $sizesArray, $brandsArray, $adminProductForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('product', $result);
        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('subcategory', $result);
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(ProductsModel::class, $result['product']);
        $this->assertInternalType('array', $result['categories']);
        $this->assertInternalType('array', $result['subcategory']);
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['brands']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormRequestHandler::handle
     * если пуста форма
     */
    public function testHandleEmptyForm()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'AdminProductForm'=>[
                        'id'=>null
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminProductDetailFormRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'AdminProductForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
