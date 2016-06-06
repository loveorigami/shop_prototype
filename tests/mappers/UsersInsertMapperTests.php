<?php

namespace app\tests\mappers;

use app\mappers\UsersInsertMapper;
use app\tests\DbManager;
use app\models\UsersModel;

/**
 * Тестирует класс app\mappers\UsersInsertMapper
 */
class UsersInsertMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод UsersInsertMapper::setOne
     */
    public function testSetOne()
    {
        $userArray = ['login'=>'user', 'password'=>'password', 'name'=>'Peter Bankman'];
        $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
        $model->attributes = $userArray;
        
        $usersInsertMapper = new UsersInsertMapper([
            'tableName'=>'users',
            'fields'=>['login', 'password', 'name'],
            'objectsOne'=>$model
        ]);
        $result = $usersInsertMapper->setOne();
        
        $this->assertEquals(1, $result);
        
        $command = \Yii::$app->db->createCommand('SELECT * FROM {{users}} WHERE login=:login');
        $command->bindValue(':login', $userArray['login']);
        $result = $command->queryOne();
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(empty($result));
        
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('login', $result);
        $this->assertArrayHasKey('password', $result);
        $this->assertArrayHasKey('name', $result);
        
        $this->assertEquals($userArray['login'], $result['login']);
        $this->assertEquals($userArray['name'], $result['name']);
        $this->assertTrue(password_verify($userArray['password'], $result['password']));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
