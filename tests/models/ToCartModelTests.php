<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\ToCartModel;

/**
 * Тестирует класс app\models\ToCartModel
 */
class ToCartModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>'app\tests\sources\fixtures\PurchasesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ToCartModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ToCartModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('TO_CART'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('id_product')); 
        $this->assertTrue(self::$_reflectionClass->hasProperty('quantity')); 
        $this->assertTrue(self::$_reflectionClass->hasProperty('id_color')); 
        $this->assertTrue(self::$_reflectionClass->hasProperty('id_size'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('price')); 
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->purchases['purchase_1'];
        
        $model = new ToCartModel(['scenario'=>ToCartModel::TO_CART]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'], 
            'quantity'=>$fixture['quantity'],
            'id_color'=>$fixture['id_color'],
            'id_size'=>$fixture['id_size'],
            'price'=>$fixture['price'],
        ];
        
        $this->assertEquals($fixture['id_product'], $model->id_product);
        $this->assertEquals($fixture['quantity'], $model->quantity);
        $this->assertEquals($fixture['id_color'], $model->id_color);
        $this->assertEquals($fixture['id_size'], $model->id_size);
        $this->assertEquals($fixture['price'], $model->price);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->purchases['purchase_1'];
        
        $model = new ToCartModel(['scenario'=>ToCartModel::TO_CART]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(5, count($model->errors));
        $this->assertTrue(array_key_exists('id_product', $model->errors));
        $this->assertTrue(array_key_exists('quantity', $model->errors));
        $this->assertTrue(array_key_exists('id_color', $model->errors));
        $this->assertTrue(array_key_exists('id_size', $model->errors));
        $this->assertTrue(array_key_exists('price', $model->errors));
        
        $model = new ToCartModel(['scenario'=>ToCartModel::TO_CART]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'], 
            'quantity'=>$fixture['quantity'],
            'id_color'=>$fixture['id_color'],
            'id_size'=>$fixture['id_size'],
            'price'=>$fixture['price'],
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
