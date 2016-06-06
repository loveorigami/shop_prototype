<?php

namespace app\queries;

use app\mappers\UsersInsertMapper;
use app\queries\UsersInsertQueryCreator;
use app\models\UsersModel;

/**
 * Тестирует класс app\queries\UsersInsertQueryCreator
 */
class UsersInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $usersInsertMapper = new UsersInsertMapper([
            'tableName'=>'users',
            'fields'=>['login', 'password', 'name'],
            'objectsOne'=>new UsersModel(['login'=>'user', 'password'=>'password']),
        ]);
        $usersInsertMapper->visit(new UsersInsertQueryCreator());
        
        $query = 'INSERT INTO {{users}} (login,password,name) VALUES (:login,:password,:name)';
        
        $this->assertEquals($query, $usersInsertMapper->query);
    }
}
