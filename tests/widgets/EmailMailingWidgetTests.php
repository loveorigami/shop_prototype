<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\EmailMailingWidget;

/**
 * Тестирует класс EmailMailingWidget
 */
class EmailMailingWidgetTests extends TestCase
{
    /**
     * Тестирует свойства EmailMailingWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(EmailMailingWidget::class);
        
        $this->assertTrue($reflection->hasProperty('mailings'));
        $this->assertTrue($reflection->hasProperty('view'));
    }
    
    /**
     * Тестирует метод EmailMailingWidget::setMailings
     * если передан неверный параметр
     * @expectedException TypeError
     */
    public function testSetMailingsError()
    {
        $mailing = new class() {};
        
        $widget = new EmailMailingWidget();
        $widget->setMailings($mailing);
    }
    
    /**
     * Тестирует метод EmailMailingWidget::setMailings
     */
    public function testSetMailings()
    {
        $mailing = new class() {};
        
        $widget = new EmailMailingWidget();
        $widget->setMailings([$mailing]);
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод EmailMailingWidget::run
     * если пуст EmailMailingWidget::mailings
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: mailings
     */
    public function testRunEmptyMailings()
    {
        $widget = new EmailMailingWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод EmailMailingWidget::run
     * если пуст EmailMailingWidget::view
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: view
     */
    public function testRunEmptyView()
    {
        $mock = new class() {};
        
        $widget = new EmailMailingWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, [$mock]);
        
        $widget->run();
    }
    
     /**
     * Тестирует метод EmailMailingWidget::run
     */
    public function testRun()
    {
        $mailings = [
            new class() {
                public $name = 'One';
                public $description = 'One description';
            },
            new class() {
                public $name = 'Two';
                public $description = 'Two description';
            },
        ];
        
        $widget = new EmailMailingWidget();
        
        $reflection = new \ReflectionProperty($widget, 'mailings');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, $mailings);
        
        $reflection = new \ReflectionProperty($widget, 'view');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'email-mailings-subscribe-success.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<h1>Привет! Это информация о ваших подписках!</h1>#', $result);
        $this->assertRegExp('#<p>Вы успешно подписались на рассылки:</p>#', $result);
        $this->assertRegExp('#<ol>#', $result);
        $this->assertRegExp('#<li>#', $result);
        $this->assertRegExp('#<strong>One</strong>#', $result);
        $this->assertRegExp('#<br>One description#', $result);
        $this->assertRegExp('#<strong>Two</strong>#', $result);
        $this->assertRegExp('#<br>Two description#', $result);
    }
}
