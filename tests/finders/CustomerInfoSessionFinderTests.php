<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CustomerInfoSessionFinder;
use app\forms\CustomerInfoForm;
use app\helpers\HashHelper;

/**
 * Тестирует класс CustomerInfoSessionFinder
 */
class CustomerInfoSessionFinderTests extends TestCase
{
    /**
     * Тестирует свойства CustomerInfoSessionFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CustomerInfoSessionFinder::class);
        
        $this->assertTrue($reflection->hasProperty('key'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CustomerInfoSessionFinder::find
     * если пуст CustomerInfoSessionFinder::key
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testFindEmptyKey()
    {
        $finder = new CustomerInfoSessionFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CustomerInfoSessionFinder::find
     */
    public function testFind()
    {
        $key = HashHelper::createCartKey();
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [
            'name'=>'John',
            'surname'=>'Doe',
            'email'=>'jahn@com.com',
            'phone'=>'+387968965',
            'address'=>'ул. Черноозерная, 1',
            'city'=>'Каркоза',
            'country'=>'Гиады',
            'postcode'=>'08789',
            'delivery'=>1,
            'payment'=>1,
        ]);
        
        $finder = new CustomerInfoSessionFinder();
        
        $reflection = new \ReflectionProperty($finder, 'key');
        $reflection->setValue($finder, $key);
        
        $form = $finder->find();
        
        $this->assertInstanceOf(CustomerInfoForm::class, $form);
        
        $reflection = new \ReflectionProperty($form, 'name');
        $result = $reflection->getValue($form);
        $this->assertSame('John', $result);
        
        $reflection = new \ReflectionProperty($form, 'surname');
        $result = $reflection->getValue($form);
        $this->assertSame('Doe', $result);
        
        $reflection = new \ReflectionProperty($form, 'email');
        $result = $reflection->getValue($form);
        $this->assertSame('jahn@com.com', $result);
        
        $reflection = new \ReflectionProperty($form, 'phone');
        $result = $reflection->getValue($form);
        $this->assertSame('+387968965', $result);
        
        $reflection = new \ReflectionProperty($form, 'address');
        $result = $reflection->getValue($form);
        $this->assertSame('ул. Черноозерная, 1', $result);
        
        $reflection = new \ReflectionProperty($form, 'city');
        $result = $reflection->getValue($form);
        $this->assertSame('Каркоза', $result);
        
        $reflection = new \ReflectionProperty($form, 'country');
        $result = $reflection->getValue($form);
        $this->assertSame('Гиады', $result);
        
        $reflection = new \ReflectionProperty($form, 'postcode');
        $result = $reflection->getValue($form);
        $this->assertSame('08789', $result);
        
        $reflection = new \ReflectionProperty($form, 'delivery');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
        
        $reflection = new \ReflectionProperty($form, 'payment');
        $result = $reflection->getValue($form);
        $this->assertSame(1, $result);
        
        $session->remove('key_test');
        $session->close();
    }
}
