<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\CategoriesMapper;
use app\models\CategoriesModel;

/**
 * Тестирует класс app\mappers\CategoriesMapper
 */
class CategoriesMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some Name';
    private static $_categorySeocode = 'mensfootwear';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
    }
    
    /**
     * Тестирует метод CategoriesMapper::getGroup
     */
    public function testGetGroup()
    {
        $categoriesMapper = new CategoriesMapper([
            'tableName'=>'categories',
            'fields'=>['id', 'name'],
        ]);
        $categoriesList = $categoriesMapper->getGroup();
        
        $this->assertTrue(is_array($categoriesList));
        $this->assertFalse(empty($categoriesList));
        $this->assertTrue(is_object($categoriesList[0]));
        $this->assertTrue($categoriesList[0] instanceof CategoriesModel);
        
        $this->assertTrue(property_exists($categoriesList[0], 'id'));
        $this->assertTrue(property_exists($categoriesList[0], 'name'));
        
        $this->assertTrue(isset($categoriesList[0]->id));
        $this->assertTrue(isset($categoriesList[0]->name));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
