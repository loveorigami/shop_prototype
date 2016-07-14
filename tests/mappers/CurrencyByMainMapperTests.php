<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\CurrencyByMainMapper;
use app\models\CurrencyModel;

/**
 * Тестирует класс app\mappers\CurrencyByMainMapper
 */
class CurrencyByMainMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_currency = 'EUR';
    private static $_exchange_rate = '12.456';
    private static $_main = '1';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{currency}} SET [[id]]=:id, [[currency]]=:currency, [[exchange_rate]]=:exchange_rate, [[main]]=:main');
        $command->bindValues([':id'=>self::$_id, ':currency'=>self::$_currency, ':exchange_rate'=>self::$_exchange_rate, ':main'=>self::$_main]);
        $command->execute();
    }
    
    /**
     * Тестирует метод CurrencyByMainMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $currencyByMainMapper = new CurrencyByMainMapper([
            'tableName'=>'currency',
            'fields'=>['id', 'currency', 'exchange_rate', 'main'],
        ]);
        $currencyModel = $currencyByMainMapper->getOneFromGroup();
        
        $this->assertTrue(is_object($currencyModel));
        $this->assertTrue($currencyModel instanceof CurrencyModel);
        
        $this->assertTrue(property_exists($currencyModel, 'id'));
        $this->assertTrue(property_exists($currencyModel, 'currency'));
        $this->assertTrue(property_exists($currencyModel, 'exchange_rate'));
        $this->assertTrue(property_exists($currencyModel, 'main'));
        
        $this->assertFalse(empty($currencyModel->id));
        $this->assertFalse(empty($currencyModel->currency));
        $this->assertFalse(empty($currencyModel->exchange_rate));
        $this->assertFalse(empty($currencyModel->main));
        
        $this->assertEquals(self::$_id, $currencyModel->id);
        $this->assertEquals(self::$_currency, $currencyModel->currency);
        $this->assertEquals(self::$_exchange_rate, $currencyModel->exchange_rate);
        $this->assertEquals(self::$_main, $currencyModel->main);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}