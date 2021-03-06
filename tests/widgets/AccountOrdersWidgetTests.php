<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AccountOrdersWidget;
use app\models\{CurrencyModel,
    UsersModel};
use app\tests\DbManager;
use app\tests\sources\fixtures\UsersFixture;
use app\forms\PurchaseForm;

/**
 * Тестирует класс AccountOrdersWidget
 */
class AccountOrdersWidgetTests extends TestCase
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
     * Тестирует свойства AccountOrdersWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountOrdersWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('form'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::setPurchases
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new AccountOrdersWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = [new class() {}];
        
        $widget = new AccountOrdersWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::setCurrency
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new AccountOrdersWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new AccountOrdersWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::setForm
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFormError()
    {
        $form = new class() {};
        
        $widget = new AccountOrdersWidget();
        $widget->setForm($form);
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::setForm
     */
    public function testSetForm()
    {
        $form = new class() extends PurchaseForm {};
        
        $widget = new AccountOrdersWidget();
        $widget->setForm($form);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PurchaseForm::class, $result);
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AccountOrdersWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AccountOrdersWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AccountOrdersWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AccountOrdersWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::run
     * если пуст AccountOrdersWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $widget = new AccountOrdersWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::run
     * если пуст AccountOrdersWidget::form
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: form
     */
    public function testRunEmptyForm()
    {
        $mock = new class() {};
        
        $widget = new AccountOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::run
     * если пуст AccountOrdersWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $widget = new AccountOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::run
     * если пуст AccountOrdersWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new AccountOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::run
     * если нет заказов
     */
    public function testRunEmptyOrders()
    {
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $statuses = ['shipped'=>'Shipped', 'canceled'=>'Canceled', 'processed'=>'Processed', 'received'=>'Received'];
        
        $form = new class() extends PurchaseForm {};
        
        $widget = new AccountOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'orders.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p>Заказов нет</p>#', $result);
    }
    
    /**
     * Тестирует метод AccountOrdersWidget::run
     */
    public function testRun()
    {
        $user = UsersModel::findOne(1);
        
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.09;
            public $code = 'MONEY';
        };
        
        $form = new class() extends PurchaseForm {};
        
        $purchases = [
            new class() {
                public $id = 2;
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 12.89;
                public $canceled = 0;
                public $shipped = 0;
                public $processed = 0;
                public $received = 1;
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
                    $this->name = new class() {
                        public $name = 'Name 1';
                    };
                    $this->surname = new class() {
                        public $surname = 'Surname 1';
                    };
                    $this->phone = new class() {
                        public $phone = 'Phone 1';
                    };
                    $this->address = new class() {
                        public $address = 'Address 1';
                    };
                    $this->city = new class() {
                        public $city = 'City 1';
                    };
                    $this->country = new class() {
                        public $country = 'Country 1';
                    };
                    $this->postcode = new class() {
                        public $postcode = 'Postcode 1';
                    };
                    $this->payment = new class() {
                        public $description = 'Payment 1';
                    };
                    $this->delivery = new class() {
                        public $description = 'Delivery 1';
                    };
                }
            },
            new class() {
                public $id = 1;
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 56.00;
                public $canceled = 0;
                public $shipped = 0;
                public $processed = 1;
                public $received = 1;
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
                    $this->name = new class() {
                        public $name = 'Name 2';
                    };
                    $this->surname = new class() {
                        public $surname = 'Surname 2';
                    };
                    $this->phone = new class() {
                        public $phone = 'Phone 2';
                    };
                    $this->address = new class() {
                        public $address = 'Address 2';
                    };
                    $this->city = new class() {
                        public $city = 'City 2';
                    };
                    $this->country = new class() {
                        public $country = 'Country 2';
                    };
                    $this->postcode = new class() {
                        public $postcode = 'Postcode 2';
                    };
                    $this->payment = new class() {
                        public $description = 'Payment 2';
                    };
                    $this->delivery = new class() {
                        public $description = 'Delivery 2';
                    };
                }
            },
            new class() {
                public $id = 3;
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 56.00;
                public $canceled = 0;
                public $shipped = 1;
                public $processed = 1;
                public $received = 1;
                public $received_date = 1459112400;
                public function __construct()
                {
                    $this->product = new class() {
                        public $seocode = 'prod_3';
                        public $name = 'Name 3';
                        public $short_description = 'Description 3';
                        public $images = 'test';
                    };
                    $this->color = new class() {
                        public $color = 'green';
                    };
                    $this->size = new class() {
                        public $size = 25.5;
                    };
                    $this->name = new class() {
                        public $name = 'Name 3';
                    };
                    $this->surname = new class() {
                        public $surname = 'Surname 3';
                    };
                    $this->phone = new class() {
                        public $phone = 'Phone 3';
                    };
                    $this->address = new class() {
                        public $address = 'Address 3';
                    };
                    $this->city = new class() {
                        public $city = 'City 3';
                    };
                    $this->country = new class() {
                        public $country = 'Country 3';
                    };
                    $this->postcode = new class() {
                        public $postcode = 'Postcode 3';
                    };
                    $this->payment = new class() {
                        public $description = 'Payment 3';
                    };
                    $this->delivery = new class() {
                        public $description = 'Delivery 3';
                    };
                }
            },
            new class() {
                public $id = 4;
                public $product;
                public $color;
                public $size;
                public $quantity = 1;
                public $price = 526.00;
                public $canceled = 1;
                public $shipped = 0;
                public $processed = 1;
                public $received = 1;
                public $received_date = 1459112400;
                public function __construct()
                {
                    $this->product = new class() {
                        public $seocode = 'prod_4';
                        public $name = 'Name 4';
                        public $short_description = 'Description 4';
                        public $images = 'test';
                    };
                    $this->color = new class() {
                        public $color = 'green';
                    };
                    $this->size = new class() {
                        public $size = 45.5;
                    };
                    $this->name = new class() {
                        public $name = 'Name 4';
                    };
                    $this->surname = new class() {
                        public $surname = 'Surname 4';
                    };
                    $this->phone = new class() {
                        public $phone = 'Phone 4';
                    };
                    $this->address = new class() {
                        public $address = 'Address 4';
                    };
                    $this->city = new class() {
                        public $city = 'City 4';
                    };
                    $this->country = new class() {
                        public $country = 'Country 4';
                    };
                    $this->postcode = new class() {
                        public $postcode = 'Postcode 4';
                    };
                    $this->payment = new class() {
                        public $description = 'Payment 4';
                    };
                    $this->delivery = new class() {
                        public $description = 'Delivery 4';
                    };
                }
            },
        ];
        
        $widget = new AccountOrdersWidget();
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $form);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'orders.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<ol class="account-orders">#', $result);
        $this->assertRegExp('#<a href=".+">Name 1</a>#', $result);
        $this->assertRegExp('#<a href=".+">Name 2</a>#', $result);
        $this->assertRegExp('#<a href=".+">Name 3</a>#', $result);
        $this->assertRegExp('#<a href=".+">Name 4</a>#', $result);
        $this->assertRegExp('#Description 1#', $result);
        $this->assertRegExp('#Description 2#', $result);
        $this->assertRegExp('#Description 3#', $result);
        $this->assertRegExp('#Description 4#', $result);
        $this->assertRegExp('#<img src=".+" height="200" alt="">#', $result);
        $this->assertRegExp('#Номер заказа:\s.+#', $result);
        $this->assertRegExp('#Дата заказа:\s.+#', $result);
        $this->assertRegExp('#Цвет:\s.+#', $result);
        $this->assertRegExp('#Размер:\s.+#', $result);
        $this->assertRegExp('#Количество:\s\d+#', $result);
        $this->assertRegExp('#Цена:\s.+\sMONEY#', $result);
        $this->assertRegExp('#Общая стоимость:\s.+\sMONEY#', $result);
        $this->assertRegExp('#Покупатель: Name \d{1} Surname \d{1}#', $result);
        $this->assertRegExp('#Телефон: Phone \d{1}#', $result);
        $this->assertRegExp('#Адрес: Address \d{1}#', $result);
        $this->assertRegExp('#Город: City \d{1}#', $result);
        $this->assertRegExp('#Страна: Country \d{1}#', $result);
        $this->assertRegExp('#Почтовый код: Postcode \d{1}#', $result);
        $this->assertRegExp('#Оплата: Payment \d{1}#', $result);
        $this->assertRegExp('#Доставка: Delivery \d{1}#', $result);
        $this->assertRegExp('#Статус:\s<span class="account-order-status">Принят</span>#', $result);
        $this->assertRegExp('#Статус:\s<span class="account-order-status">Выполняется</span>#', $result);
        $this->assertRegExp('#Статус:\s<span class="account-order-status">Доставлен</span>#', $result);
        $this->assertRegExp('#Статус:\s<span class="account-order-status">Отменен</span>#', $result);
        $this->assertRegExp('#<form id="order-cancellation-form-\d{1}" action=".+" method="POST">#', $result);
        $this->assertRegExp('#<input type="hidden" id=".+" class="form-control" name=".+\[id\]" value="\d{1}">#', $result);
        $this->assertRegExp('#<input type="submit" value="Отменить">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
