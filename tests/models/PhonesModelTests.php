<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\PhonesModel;

/**
 * Тестирует PhonesModel
 */
class PhonesModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_phone = '+396548971203';
    private static $_phone2 = '+380506589878';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\PhonesModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{phones}} SET [[id]]=:id, [[phone]]=:phone');
        $command->bindValues([':id'=>self::$_id, ':phone'=>self::$_phone]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new PhonesModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        
        $this->assertTrue(property_exists($model, '_id'));
        $this->assertTrue(property_exists($model, 'phone'));
        
        $this->assertTrue(method_exists($model, 'getId'));
        $this->assertTrue(method_exists($model, 'setId'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id + 1, 'phone'=>self::$_phone2];
        
        $this->assertTrue(empty($model->id));
        $this->assertFalse(empty($model->phone));
        
        $this->assertNotEquals(self::$_id + 1, $model->id);
        $this->assertEquals(self::$_phone2, $model->phone);
        
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id + 9, 'phone'=>self::$_phone2];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->phone));
        
        $this->assertEquals(self::$_id + 9, $model->id);
        $this->assertEquals(self::$_phone2, $model->phone);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_FORM]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('phone', $model->errors));
        
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_FORM]);
        $model->attributes = ['phone'=>self::$_phone];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует метод PhonesModel::getId
     */
    public function testGetId()
    {
        $model = new PhonesModel();
        $model->phone = self::$_phone;
        
        $this->assertEquals(self::$_id, $model->id);
    }
    
    /**
     * Тестирует выброс исключения в методе PhonesModel::getId
     * @expectedException ErrorException
     */
    public function testExcGetId()
    {
        $model = new PhonesModel();
        //$model->phone = self::$_phone;
        
       $model->id;
    }
    
    /**
     * Тестирует метод PhonesModel::setId
     */
    public function testSetId()
    {
        $model = new PhonesModel();
        $model->id = self::$_id + 3;
        
        $this->assertEquals(self::$_id + 3, $model->id);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}