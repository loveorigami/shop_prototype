<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\{EmailsModel,
    UsersModel};

/**
 * Тестирует класс app\models\EmailsModel
 */
class EmailsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'emails'=>'app\tests\sources\fixtures\EmailsFixture',
                'users'=>'app\tests\sources\fixtures\UsersFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\EmailsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\EmailsModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_AUTHENTICATION'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_REGISTRATION'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ORDER'));
        
        $model = new EmailsModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('email', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->emails['email_1'];
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_AUTHENTICATION]);
        $model->attributes = [
            'email'=>$fixture['email'], 
        ];
        
        $this->assertEquals($fixture['email'], $model->email);
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_REGISTRATION]);
        $model->attributes = [
            'email'=>$fixture['email'], 
        ];
        
        $this->assertEquals($fixture['email'], $model->email);
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_ORDER]);
        $model->attributes = [
            'email'=>$fixture['email'], 
        ];
        
        $this->assertEquals($fixture['email'], $model->email);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_AUTHENTICATION]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
        
        $fixture = self::$_dbClass->emails['email_1'];
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_AUTHENTICATION]);
        $model->email = $fixture['email'];
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_REGISTRATION]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_REGISTRATION]);
        $model->email = 'some@some.com';
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_ORDER]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('email', $model->errors));
        
        $model = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_ORDER]);
        $model->email = 'some@some.com';
        $model->validate();
        
        $this->assertEquals(0, count($model->errors));
    }
    
    /**
     * Тестирует поля, возвращаемые Model::toArray()
     */
    public function testToArray()
    {
        $fixture = self::$_dbClass->emails['email_1'];
        
        $model = new EmailsModel();
        $model->email = $fixture['id'];
        $model->email = $fixture['email'];
        
        $result = $model->toArray();
        
        $this->assertEquals(2, count($result));
        $this->assertTrue(array_key_exists('id', $result));
        $this->assertTrue(array_key_exists('email', $result));
    }
    
    /**
     * Тестирует метод EmailsModel::getUser
     */
    public function testGetUser()
    {
        $fixture = self::$_dbClass->emails['email_1'];
        
        $model = EmailsModel::find()->where(['emails.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->user instanceof UsersModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $emailsQuery = EmailsModel::find();
        $emailsQuery->extendSelect(['id', 'email']);
        
        $queryRaw = clone $emailsQuery;
        
        $expectedQuery = "SELECT `emails`.`id`, `emails`.`email` FROM `emails`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $emailsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof EmailsModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->emails['email_1'];
        
        $emailsQuery = EmailsModel::find();
        $emailsQuery->extendSelect(['id', 'email']);
        $emailsQuery->where(['emails.email'=>$fixture['email']]);
        
        $queryRaw = clone $emailsQuery;
        
        $expectedQuery = sprintf("SELECT `emails`.`id`, `emails`.`email` FROM `emails` WHERE `emails`.`email`='%s'", $fixture['email']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $emailsQuery->one();
        
        $this->assertTrue(is_object($result));
        $this->assertTrue($result instanceof EmailsModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->emails['email_1'];
        $fixture2 = self::$_dbClass->emails['email_2'];
        
        $emailsQuery = EmailsModel::find();
        $emailsQuery->extendSelect(['id', 'email']);
        $emailsArray = $emailsQuery->allMap('id', 'email');
        
        $this->assertFalse(empty($emailsArray));
        $this->assertTrue(array_key_exists($fixture['id'], $emailsArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $emailsArray));
        $this->assertTrue(in_array($fixture['email'], $emailsArray));
        $this->assertTrue(in_array($fixture2['email'], $emailsArray));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
