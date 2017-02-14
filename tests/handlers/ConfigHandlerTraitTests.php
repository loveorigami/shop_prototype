<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\User;
use app\handlers\ConfigHandlerTrait;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture};
use app\models\{CategoriesModel,
    CurrencyInterface,
    CurrencyModel};
use app\collections\{CollectionInterface,
    LightPagination,
    PaginationInterface,
    ProductsCollection,
    PurchasesCollection,
    PurchasesCollectionInterface};
use app\forms\AbstractBaseForm;
use app\controllers\ProductsListController;
use app\exceptions\ExceptionsTrait;

/**
 * Тестирует класс ConfigHandlerTrait
 */
class ConfigHandlerTraitTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'categories'=>CategoriesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new ProductsListController('list', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new class() {
            use ConfigHandlerTrait, ExceptionsTrait;
        };
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::userInfoWidgetConfig
     */
    public function testUserInfoWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'userInfoWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, \Yii::$app->user);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::shortCartWidgetConfig
     */
    public function testShortCartWidgetConfig()
    {
        $currencyModel = new class() extends CurrencyModel {};
        $ordersCollection = new class() extends PurchasesCollection {};
        
        $reflection = new \ReflectionMethod($this->handler, 'shortCartWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $ordersCollection, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::currencyWidgetConfig
     */
    public function testCurrencyWidgetConfig()
    {
        $currencyArray = [new class() extends CurrencyModel {}];
        $changeCurrencyForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'currencyWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $currencyArray, $changeCurrencyForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::searchWidgetConfig
     */
    public function testSearchWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'searchWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, 'search');
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['text']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::categoriesMenuWidgetConfig
     */
    public function testCategoriesMenuWidgetConfig()
    {
        $categoriesModelArray = [new class() extends CategoriesModel {}];
        
        $reflection = new \ReflectionMethod($this->handler, 'categoriesMenuWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $categoriesModelArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('categories', $result);
        $this->assertInternalType('array', $result['categories']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::accountMailingsUnsubscribeWidgetConfig
     */
    public function testAccountMailingsUnsubscribeWidgetConfig()
    {
        $mailingsArray = [new class() {}];
        $mailingForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'accountMailingsUnsubscribeWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsArray, $mailingForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::accountMailingsFormWidgetConfig
     */
    public function testAccountMailingsFormWidgetConfig()
    {
        $mailingsArray = [new class() {}];
        $mailingForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'accountMailingsFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $mailingsArray, $mailingForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AccountOrdersRequestHandler::оrdersFiltersWidgetConfig
     */
    public function testOrdersFiltersWidgetConfig()
    {
        $sortingTypesArray = [new class() {}];
        $statusesArray = [new class() {}];
        $ordersFiltersForm = new class() extends AbstractBaseForm {
            public $sortingType;
            public $dateFrom;
            public $dateTo;
            public $url;
        };
        
        $reflection = new \ReflectionMethod($this->handler, 'оrdersFiltersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $sortingTypesArray, $statusesArray, $ordersFiltersForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('sortingTypes', $result);
        $this->assertArrayhasKey('statuses', $result);
        $this->assertArrayhasKey('form', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInternalType('array', $result['statuses']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AccountOrdersRequestHandler::paginationWidgetConfig
     */
    public function testPaginationWidgetConfig()
    {
        $pagination = new class() extends LightPagination {};
        
        $reflection = new \ReflectionMethod($this->handler, 'paginationWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $pagination);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('pagination', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(PaginationInterface::class, $result['pagination']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::shortCartWidgetAjaxConfig
     * если запрос с ошибками
     */
    public function testShortCartWidgetAjaxConfig()
    {
        $purchasesCollection = new class() extends PurchasesCollection {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'shortCartWidgetAjaxConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $purchasesCollection, $currentCurrencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::cartWidgetConfig
     * если запрос с ошибками
     */
    public function testCartWidgetConfig()
    {
        $purchasesCollection = new class() extends PurchasesCollection {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        $updateForm = new class() extends AbstractBaseForm {};
        $deleteForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'cartWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $purchasesCollection, $currentCurrencyModel, $updateForm, $deleteForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('updateForm', $result);
        $this->assertArrayHasKey('deleteForm', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['updateForm']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['deleteForm']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::shortCartRedirectWidgetConfig
     * если запрос с ошибками
     */
    public function testShortCartRedirectWidgetConfig()
    {
        $purchasesCollection = new class() extends PurchasesCollection {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'shortCartRedirectWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $purchasesCollection, $currentCurrencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::emptyProductsWidgetConfig
     */
    public function testEmptyProductsWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'emptyProductsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('template', $result);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ConfigHandlerTrait::productsWidgetConfig
     */
    public function testProductsWidgetConfig()
    {
        $productsCollection = new class() extends ProductsCollection {};
        $currencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'productsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $productsCollection, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(CollectionInterface::class, $result['products']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}