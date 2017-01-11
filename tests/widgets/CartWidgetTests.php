<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\CartWidget;
use app\collections\PurchasesCollection;
use app\models\CurrencyModel;

/**
 * Тестирует класс CartWidget
 */
class CartWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CartWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('view'));
        $this->assertTrue($reflection->hasProperty('goods'));
        $this->assertTrue($reflection->hasProperty('cost'));
    }
    
    /**
     * Тестирует метод CartWidget::setPurchases
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new CartWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод CartWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $widget = new CartWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PurchasesCollection::class, $result);
    }
    
    /**
     * Тестирует метод CartWidget::setCurrency
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new CartWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод CartWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new CartWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод CartWidget::run
     * при отсутствии CartWidget::purchases
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: purchases
     */
    public function testRunErrorPurchases()
    {
        $widget = new CartWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод CartWidget::run
     * если CartWidget::purchases пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: purchases
     */
    public function testRunEmptyPurchases()
    {
        $purchases = new class() extends PurchasesCollection {};
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CartWidget::run
     * при отсутствии CartWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $purchases = new class() extends PurchasesCollection {
            protected $items = [1];
        };
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CartWidget::run
     * при отсутствии CartWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $purchases = new class() extends PurchasesCollection {
            protected $items = [1];
        };
        
        $currency = new class() extends CurrencyModel {};
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод CartWidget::run
     */
    public function testRun()
    {
        $items = [
            new class() {
                public $id_product = 1;
                public $quantity = 1;
                public $price = 123.67;
                public $product;
                public function __construct()
                {
                    $this->product = new class() {
                        public $name = 'Product 1';
                        public $seocode = 'product_1';
                        public $short_description = 'Short description';
                        public $images = 'test';
                    };
                }
            },
            new class() {
                public $id_product = 2;
                public $quantity = 1;
                public $price = 85.00;
                public $product;
                public function __construct()
                {
                    $this->product = new class() {
                        public $name = 'Product 2';
                        public $seocode = 'product_2';
                        public $short_description = 'Short description';
                        public $images = 'test';
                    };
                }
            },
        ];
        
        $purchases = new class() extends PurchasesCollection {
            protected $items;
        };
        $reflection = new \ReflectionProperty($purchases, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($purchases, $items);
        
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $widget = new CartWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'cart.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<li class="product-id-1">#', $result);
        $this->assertRegExp('#<a href=".+">Product 1</a>#', $result);
        $this->assertRegExp('#Short description#', $result);
        $this->assertRegExp('#<span class="price">258,47 MONEY</span>#', $result);
        $this->assertRegExp('#<img src=".+" alt="">#', $result);
        $this->assertRegExp('#<li class="product-id-2">#', $result);
        $this->assertRegExp('#<a href=".+">Product 2</a>#', $result);
        $this->assertRegExp('#<span class="price">177,65 MONEY</span>#', $result);
        $this->assertRegExp('#<p>Товаров в корзине: 2, Общая стоимость: 436,12 MONEY</p>#', $result);
    }
}
