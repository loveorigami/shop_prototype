<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AdminOrdersWidget;
use app\models\{CurrencyModel,
    UsersModel};
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;

/**
 * Тестирует класс AdminOrdersWidget
 */
class AdminOrdersWidgetTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AdminOrdersWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrdersWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод AdminOrdersWidget::setPurchases
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new AdminOrdersWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод AdminOrdersWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = [new class() {}];
        
        $widget = new AdminOrdersWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminOrdersWidget::setCurrency
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new AdminOrdersWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод AdminOrdersWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new AdminOrdersWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод AdminOrdersWidget::run
     * если пуст AdminOrdersWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $widget = new AdminOrdersWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrdersWidget::run
     * если пуст AdminOrdersWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $widget = new AdminOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrdersWidget::run
     * если пуст AdminOrdersWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $widget = new AdminOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AdminOrdersWidget::run
     * если нет оформленных покупок
     */
    public function testRunNotPurchases()
    {
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $widget = new AdminOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-purchases.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Сегодня заказов нет</p>#', $result);
    }
    
    /**
     * Тестирует метод AdminOrdersWidget::run
     * если есть оформленные покупки
     */
    public function testRunExistProcessedPurchases()
    {
        $user = UsersModel::findOne(1);
        
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $purchases = [
            new class() {
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 12.89;
                public $received = 1;
                public $canceled = 0;
                public $shipped = 0;
                public $processed = 0;
                public $received_date = 1459112400;
                public function __construct()
                {
                    $this->product = new class() {
                        public $seocode = 'prod_1';
                        public $name = 'Name 1';
                        public $short_description = 'Description 1';
                        public $images = 'test';
                    };
                    $this->color = new class() {
                        public $color = 'gray';
                    };
                    $this->size = new class() {
                        public $size = 45;
                    };
                }
            },
            new class() {
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 56.00;
                public $received = 1;
                public $canceled = 0;
                public $shipped = 0;
                public $processed = 1;
                public $received_date = 1459112400;
                public function __construct()
                {
                    $this->product = new class() {
                        public $seocode = 'prod_2';
                        public $name = 'Name 2';
                        public $short_description = 'Description 2';
                        public $images = 'test';
                    };
                    $this->color = new class() {
                        public $color = 'green';
                    };
                    $this->size = new class() {
                        public $size = 15.5;
                    };
                }
            },
        ];
        
        $widget = new AdminOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'admin-purchases.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<ol class="account-last-orders">#', $result);
        $this->assertRegExp('#<a href="../vendor/phpunit/phpunit/prod_1">Name 1</a>#', $result);
        $this->assertRegExp('#<br>Description 1#', $result);
        $this->assertRegExp('#<br><img src=".+" height="200" alt="">#', $result);
        $this->assertRegExp('#<br>Дата заказа:\s.+#', $result);
        $this->assertRegExp('#<br>Цвет: gray#', $result);
        $this->assertRegExp('#<br>Размер: 45#', $result);
        $this->assertRegExp('#<br>Количество: 1#', $result);
        $this->assertRegExp('#<br>Цена: 26,94 MONEY#', $result);
        $this->assertRegExp('#<br>Статус: Принят#', $result);
        $this->assertRegExp('#<a href="../vendor/phpunit/phpunit/prod_2">Name 2</a>#', $result);
        $this->assertRegExp('#<br>Description 2#', $result);
        $this->assertRegExp('#<br><img src=".+" height="200" alt="">#', $result);
        $this->assertRegExp('#<br>Цвет: green#', $result);
        $this->assertRegExp('#<br>Размер: 15.5#', $result);
        $this->assertRegExp('#<br>Количество: 1#', $result);
        $this->assertRegExp('#<br>Цена: 117,04 MONEY#', $result);
        $this->assertRegExp('#<br>Статус: Выполняется#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
