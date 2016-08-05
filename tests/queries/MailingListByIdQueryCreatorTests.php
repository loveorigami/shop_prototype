<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\MailingListByIdQueryCreator;

/**
 * Тестирует класс app\queries\MailingListByIdQueryCreator
 */
class MailingListByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'mailing_list',
            'fields'=>['id', 'name', 'description'],
        ]);
        
        $queryCreator = new MailingListByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[mailing_list.id]],[[mailing_list.name]],[[mailing_list.description]] FROM {{mailing_list}} WHERE [[mailing_list.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
