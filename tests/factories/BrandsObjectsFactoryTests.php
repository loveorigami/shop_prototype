<?php

namespace app\tests\factories;

use app\factories\BrandsObjectsFactory;
use app\tests\DbManager;
use app\models\BrandsModel;
use app\mappers\BrandsMapper;
use app\queries\BrandsQueryCreator;

/**
 * Тестирует класс app\factories\BrandsObjectsFactory
 */
class BrandsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод BrandsObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $brandsMapper = new BrandsMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'orderByField'=>'brand'
        ]);
        
        $this->assertEmpty($brandsMapper->objectsArray);
        $this->assertEmpty($brandsMapper->DbArray);
        
        $brandsMapper->visit(new BrandsQueryCreator());
        
        $brandsMapper->DbArray = \Yii::$app->db->createCommand($brandsMapper->query)->queryAll();
        
        $this->assertFalse(empty($brandsMapper->DbArray));
        
        $brandsMapper->visit(new BrandsObjectsFactory());
        
        $this->assertFalse(empty($brandsMapper->objectsArray));
        $this->assertTrue(is_object($brandsMapper->objectsArray[0]));
        $this->assertTrue($brandsMapper->objectsArray[0] instanceof BrandsModel);
        
        $this->assertTrue(property_exists($brandsMapper->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($brandsMapper->objectsArray[0], 'brand'));
        
        $this->assertTrue(isset($brandsMapper->objectsArray[0]->id));
        $this->assertTrue(isset($brandsMapper->objectsArray[0]->brand));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
