<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\widgets\PasswordGenerateEmptyWidget;

/**
 * Тестирует класс PasswordGenerateEmptyWidget
 */
class PasswordGenerateEmptyWidgetTests extends TestCase
{
    /**
     * Тестирует свойства PasswordGenerateEmptyWidget
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PasswordGenerateEmptyWidget::class);
        
        $this->assertTrue($reflection->hasProperty('template'));
    }
    
    /**
     * Тестирует метод PasswordGenerateEmptyWidget::setTemplate
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetTemplateError()
    {
        $template = null;
        
        $widget = new PasswordGenerateEmptyWidget();
        $widget->setTemplate($template);
    }
    
    /**
     * Тестирует метод PasswordGenerateEmptyWidget::setTemplate
     */
    public function testSetTemplate()
    {
        $template = 'Template';
        
        $widget = new PasswordGenerateEmptyWidget();
        $widget->setTemplate($template);
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод PasswordGenerateEmptyWidget::run
     * если пуст PasswordGenerateEmptyWidget::template
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: template
     */
    public function testRunEmptyTemplate()
    {
        $widget = new PasswordGenerateEmptyWidget();
        $widget->run();
    }
    
    /**
     * Тестирует метод PasswordGenerateEmptyWidget::run
     */
    public function testRun()
    {
        $widget = new PasswordGenerateEmptyWidget();
        
        $reflection = new \ReflectionProperty($widget, 'template');
        $reflection->setAccessible(true);
        $reflection->setValue($widget, 'generate-empty.twig');
        
        $result = $widget->run();
        
        $this->assertRegExp('#<p><strong>Восстановление пароля</strong></p>#', $result);
        $this->assertRegExp('#<p>К сожалению, ссылка по которой вы перешли недействительна. Для решения этой проблемы вы можете обратиться к администратору</p>#', $result);
    }
}
