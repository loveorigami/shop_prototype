<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\MailingsWidget;

/**
 * Тестирует класс MailingsWidget
 */
class MailingsWidgetTests extends TestCase
{
    /**
     * Тестирует свойства MailingsWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(MailingsWidget::class);
        
        $this->assertTrue($reflection->hasProperty('mailings'));
        $this->assertTrue($reflection->hasProperty('header'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод MailingsWidget::setMailings
     * если передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetMailingsError()
    {
        $mock = new class() {};
        
        $widget = new MailingsWidget();
        $widget->setMailings($mock);
    }
    
    /**
     * Тестирует метод MailingsWidget::setMailings
     */
    public function testSetMailings()
    {
        $mock = [new class() {}];
        
        $widget = new MailingsWidget();
        $widget->setMailings($mock);
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод MailingsWidget::run
     * если пуст MailingsWidget::header
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: header
     */
    public function testRunEmptyHeader()
    {
        $widget = new MailingsWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод MailingsWidget::run
     * если пуст MailingsWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $widget = new MailingsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'header');
        
        $widget->run();
    }
    
    /**
     * Тестирует метод MailingsWidget::run
     * если нет доступных подписок
     */
    public function testRunNotExists()
    {
        $mailings = [];
        
        $widget = new MailingsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'mailings.twig');
        
        $result = $widget->run();
        
        $this->assertEmpty($result);
    }
    
    /**
     * Тестирует метод MailingsWidget::run
     */
    public function testRun()
    {
        $mailings = [
            new class() {
                public $id = 1;
                public $name = 'One';
                public $description = 'One description';
            },
            new class() {
                public $id = 2;
                public $name = 'Two';
                public $description = 'Two description';
            },
        ];
        
        $widget = new MailingsWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'header');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'Header');
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'mailings.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Header</strong></p>#', $result);
        $this->assertRegExp('#One#', $result);
        $this->assertRegExp('#One description#', $result);
        $this->assertRegExp('#Two#', $result);
        $this->assertRegExp('#Two description#', $result);
    }
}
