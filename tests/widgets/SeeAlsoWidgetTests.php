<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\SeeAlsoWidget;
use app\models\CurrencyModel;

/**
 * Тестирует класс SeeAlsoWidget
 */
class SeeAlsoWidgetTests extends TestCase
{
    /**
     * Тестирует наличие свойств SeeAlsoWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SeeAlsoWidget::class);
        
        $this->assertTrue($reflection->hasProperty('products'));
        $this->assertTrue($reflection->hasProperty('currency'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setProducts
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProducstError()
    {
        $products = new class() {};
        
        $widget = new SeeAlsoWidget();
        $widget->setProducts($products);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setProducts
     */
    public function testSetProducts()
    {
        $products = [new class() {}];
        
        $widget = new SeeAlsoWidget();
        $widget->setProducts($products);
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setCurrency
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCurrencyError()
    {
        $currency = new class() {};
        
        $widget = new SeeAlsoWidget();
        $widget->setCurrency($currency);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setCurrency
     */
    public function testSetCurrency()
    {
        $currency = new class() extends CurrencyModel {};
        
        $widget = new SeeAlsoWidget();
        $widget->setCurrency($currency);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setHeader
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetHeaderError()
    {
        $header = null;
        
        $widget = new SeeAlsoWidget();
        $widget->setHeader($header);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setHeader
     */
    public function testSetHeader()
    {
        $header = 'Header';
        
        $widget = new SeeAlsoWidget();
        $widget->setHeader($header);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new SeeAlsoWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new SeeAlsoWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::run
     */
    public function testRunEmptyProducts()
    {
        $widget = new SeeAlsoWidget();
        $result = $widget->run();
        
        $this->assertSame('', $result);
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::run
     * если пуст SeeAlsoWidget::currency
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: currency
     */
    public function testRunEmptyCurrency()
    {
        $products = [new class() {}];
        
        $widget = new SeeAlsoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $products);
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::run
     * если пуст SeeAlsoWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $products = [new class() {}];
        $currency = new class() extends CurrencyModel {};
        
        $widget = new SeeAlsoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $products);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $result = $widget->run();
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::run
     * если пуст SeeAlsoWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyView()
    {
        $products = [new class() {}];
        $currency = new class() extends CurrencyModel {};
        
        $widget = new SeeAlsoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $products);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод SeeAlsoWidget::run
     */
    public function testRun()
    {
        $product_1 = new class() {
            public $name = 'One';
            public $seocode = 'one';
            public $images = 'test';
            public $price = 135;
        };
        
        $product_2 = new class() {
            public $name = 'Two';
            public $seocode = 'two';
            public $images = 'test';
            public $price = 98.56;
        };
        
        $currency = new class() extends CurrencyModel {
            public $exchange_rate = 2.4587;
            public $code = 'MONEY';
        };
        
        $widget = new SeeAlsoWidget();
        
        $reflection = new \ReflectionProperty($widget, 'products');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$product_1, $product_2]);
        
        $reflection = new \ReflectionProperty($widget, 'currency');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $currency);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'see-also.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<div class="product-name"><a href=".+" class="product-text-link">One</a>#', $result);
        $this->assertRegExp('#<img src=".+" height="150" alt="">#', $result);
        $this->assertRegExp('#<span class="price">331,92 MONEY</span>#', $result);
        $this->assertRegExp('#<div class="product-name"><a href=".+" class="product-text-link">Two</a>#', $result);
        $this->assertRegExp('#<img src=".+" height="150" alt="">#', $result);
        $this->assertRegExp('#<span class="price">242,33 MONEY</span>#', $result);
    }
}
