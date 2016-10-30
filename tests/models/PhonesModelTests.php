<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\PhonesModel;

/**
 * Тестирует класс app\models\PhonesModel
 */
class PhonesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'phones'=>'app\tests\sources\fixtures\PhonesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\PhonesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\PhonesModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ORDER'));
        
        $model = new PhonesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('phone', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->phones['phone_1'];
        
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_ORDER]);
        $model->attributes = [
            'phone'=>$fixture['phone'], 
        ];
        
        $this->assertEquals($fixture['phone'], $model->phone);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->phones['phone_1'];
        
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_ORDER]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('phone', $model->errors));
        
        $model = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_ORDER]);
        $model->attributes = [
            'phone'=>$fixture['phone'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует поля, возвращаемые Model::toArray()
     */
    public function testToArray()
    {
        $fixture = self::$_dbClass->phones['phone_1'];
        
        $model = new PhonesModel();
        $model->phone = $fixture['id'];
        $model->phone = $fixture['phone'];
        
        $result = $model->toArray();
        
        $this->assertEquals(2, count($result));
        $this->assertTrue(array_key_exists('id', $result));
        $this->assertTrue(array_key_exists('phone', $result));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $phonesQuery = PhonesModel::find();
        $phonesQuery->extendSelect(['id', 'phone']);
        
        $queryRaw = clone $phonesQuery;
        
        $expectedQuery = "SELECT `phones`.`id`, `phones`.`phone` FROM `phones`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $phonesQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof PhonesModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->phones['phone_1'];
        
        $phonesQuery = PhonesModel::find();
        $phonesQuery->extendSelect(['id', 'phone']);
        $phonesQuery->where(['phones.phone'=>$fixture['phone']]);
        
        $queryRaw = clone $phonesQuery;
        
        $expectedQuery = sprintf("SELECT `phones`.`id`, `phones`.`phone` FROM `phones` WHERE `phones`.`phone`='%s'", $fixture['phone']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $phonesQuery->one();
        
        $this->assertTrue($result instanceof PhonesModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->phones['phone_1'];
        $fixture2 = self::$_dbClass->phones['phone_2'];
        
        $phonesQuery = PhonesModel::find();
        $phonesQuery->extendSelect(['id', 'phone']);
        $phonesArray = $phonesQuery->allMap('id', 'phone');
        
        $this->assertFalse(empty($phonesArray));
        $this->assertTrue(array_key_exists($fixture['id'], $phonesArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $phonesArray));
        $this->assertTrue(in_array($fixture['phone'], $phonesArray));
        $this->assertTrue(in_array($fixture2['phone'], $phonesArray));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
