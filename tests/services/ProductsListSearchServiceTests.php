<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\{ProductsListSearchService,
    ServiceInterface};
use app\models\{CategoriesModel,
    CurrencyModel,
    SubcategoryModel};
use app\collections\CollectionInterface;
use app\widgets\{PaginationWidget,
    PriceWidget,
    ThumbnailsWidget};
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SubcategoryFixture};
use app\forms\FiltersForm;

/**
 * Тестирует класс ProductsListSearchService
 */
class ProductsListSearchServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'colors'=>ProductsColorsFixture::class,
                'sizes'=>ProductsSizesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует метод ProductsListSearchService::setCommonService
     * если передан аргумент неверного типа
     * @expectedException TypeError
     */
    public function testSetCommonServiceError()
    {
        $commonService = new class() {};
        $service = new ProductsListSearchService();
        $service->setCommonService($commonService);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::setCommonService
     */
    public function testSetCommonService()
    {
        $commonService = new class() implements ServiceInterface {
            public function handle($data) {}
        };
        $service = new ProductsListSearchService();
        $service->setCommonService($commonService);
        
        $reflection = new \ReflectionProperty($service, 'commonService');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($service);
        
        $this->assertInstanceOf(ServiceInterface::class, $result);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     */
    public function testHandle()
    {
        $request = [\Yii::$app->params['searchKey']=>'рубашка'];
        
        $commonService = new class() implements ServiceInterface {
            public function handle($data) {
                return ['currencyModel'=>new CurrencyModel(['code'=>'MONEY', 'exchange_rate'=>7.0975, 'main'=>true])];
            }
        };
        
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionProperty($service, 'commonService');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($service, $commonService);
        
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('productsConfig', $result);
        $this->assertArrayHasKey('productsCollection', $result['productsConfig']);
        $this->assertArrayHasKey('priceWidget', $result['productsConfig']);
        $this->assertArrayHasKey('thumbnailsWidget', $result['productsConfig']);
        $this->assertArrayHasKey('paginationWidget', $result['productsConfig']);
        $this->assertArrayHasKey('view', $result['productsConfig']);
        $this->assertInstanceOf(CollectionInterface::class, $result['productsConfig']['productsCollection']);
        $this->assertInstanceOf(PriceWidget::class, $result['productsConfig']['priceWidget']);
        $this->assertInstanceOf(ThumbnailsWidget::class, $result['productsConfig']['thumbnailsWidget']);
        $this->assertInstanceOf(PaginationWidget::class, $result['productsConfig']['paginationWidget']);
        $this->assertInternalType('string', $result['productsConfig']['view']);
        
        $this->assertArrayHasKey('filtersConfig', $result);
        $this->assertArrayHasKey('colorsCollection', $result['filtersConfig']);
        $this->assertArrayHasKey('sizesCollection', $result['filtersConfig']);
        $this->assertArrayHasKey('brandsCollection', $result['filtersConfig']);
        $this->assertArrayHasKey('form', $result['filtersConfig']);
        $this->assertArrayHasKey('view', $result['filtersConfig']);
        $this->assertInstanceOf(CollectionInterface::class, $result['filtersConfig']['colorsCollection']);
        $this->assertInstanceOf(CollectionInterface::class, $result['filtersConfig']['sizesCollection']);
        $this->assertInstanceOf(CollectionInterface::class, $result['filtersConfig']['brandsCollection']);
        $this->assertInstanceOf(FiltersForm::class, $result['filtersConfig']['form']);
        $this->assertInternalType('string', $result['filtersConfig']['view']);
        
        $this->assertArrayNotHasKey('emptySphinxConfig', $result);
    }
    
    /**
     * Тестирует метод ProductsListSearchService::handle
     * если sphinx ничего не нашел
     */
    public function testHandleSphinxEmpty()
    {
        $request = [\Yii::$app->params['searchKey']=>'этого нет'];
        
        $commonService = new class() implements ServiceInterface {
            public function handle($data) {
                return ['currencyModel'=>new CurrencyModel(['code'=>'MONEY', 'exchange_rate'=>7.0975, 'main'=>true])];
            }
        };
        
        $service = new ProductsListSearchService();
        
        $reflection = new \ReflectionProperty($service, 'commonService');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($service, $commonService);
        
        $result = $service->handle($request);
        
        $this->assertNotEmpty($result);
        $this->assertInternalType('array', $result);
        
        $this->assertArrayNotHasKey('productsConfig', $result);
        $this->assertArrayNotHasKey('filtersConfig', $result);
        
        $this->assertArrayHasKey('emptySphinxConfig', $result);
        $this->assertArrayHasKey('text', $result['emptySphinxConfig']);
        $this->assertArrayHasKey('view', $result['emptySphinxConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
