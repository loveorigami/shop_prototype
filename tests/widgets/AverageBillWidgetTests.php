<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\AverageBillWidget;
use app\collections\{PurchasesCollection,
    PurchasesCollectionInterface};
use app\models\CurrencyModel;

/**
 * Тестирует класс AverageBillWidget
 */
class AverageBillWidgetTests extends TestCase
{
    /**
     * Тестирует свойства AverageBillWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AverageBillWidget::class);
        
        $this->assertTrue($reflection->hasProperty('purchases'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод AverageBillWidget::setPurchases
     * передаю неверный тип параметра
     * @expectedException TypeError
     */
    public function testSetPurchasesError()
    {
        $purchases = new class() {};
        
        $widget = new AverageBillWidget();
        $widget->setPurchases($purchases);
    }
    
    /**
     * Тестирует метод AverageBillWidget::setPurchases
     */
    public function testSetPurchases()
    {
        $purchases = new class() extends PurchasesCollection{};
        
        $widget = new AverageBillWidget();
        $widget->setPurchases($purchases);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result);
    }
    
    /**
     * Тестирует метод AverageBillWidget::setCurrency
     * передаю неверный тип параметра
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currenct = new class() {};
        
        $widget = new AverageBillWidget();
        $widget->setCurrency($currenct);
    }
    
    /**
     * Тестирует метод AverageBillWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel{};
        
        $widget = new AverageBillWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод AverageBillWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new AverageBillWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод AverageBillWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new AverageBillWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AverageBillWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new AverageBillWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод AverageBillWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new AverageBillWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод AverageBillWidget::run
     * если пуст AverageBillWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $widget = new AverageBillWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод AverageBillWidget::run
     * если пуст AverageBillWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $mock = new class() {};
        
        $widget = new AverageBillWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AverageBillWidget::run
     * если пуст AverageBillWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $mock = new class() {};
        
        $widget = new AverageBillWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mock);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод AverageBillWidget::run
     * если покупок нет
     */
    public function testRunEmpty()
    {
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.23;
            public $code = 'MONEY';
        };
        
        $purchases = new class() {
            public function isEmpty()
            {
                return true;
            }
        };
        
        $widget = new AverageBillWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'average-bill.twig');
        
        $result = $widget->run();
        
        //$this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Средний чек:</strong> 0,00 MONEY</p>#', $result);
    }
    
    /**
     * Тестирует метод AverageBillWidget::run
     */
    public function testRun()
    {
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.23;
            public $code = 'MONEY';
        };
        
        $purchases = new class() {
            public function isEmpty()
            {
                return false;
            }
            public function totalPrice()
            {
                return 100.00;
            }
            public function count()
            {
                return 3;
            }
        };
        
        $widget = new AverageBillWidget();
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'purchases');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $purchases);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'average-bill.twig');
        
        $result = $widget->run();
        
        //$this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#<p><strong>Средний чек:</strong> 74,33 MONEY</p>#', $result);
    }
}
