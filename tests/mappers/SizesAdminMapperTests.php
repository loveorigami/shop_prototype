<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\SizesAdminMapper;
use app\models\SizesModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\SizesAdminMapper
 */
class SizesAdminMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some Name';
    private static $_size = '45';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_sizes}} SET [[id_products]]=:id_products, [[id_sizes]]=:id_sizes');
        $command->bindValues([':id_products'=>self::$_id, ':id_sizes'=>self::$_id]);
        $command->execute();
        
        \Yii::$app->filters->clean();
        \Yii::$app->filters->cleanOther();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод SizesAdminMapper::getGroup
     */
    public function testGetGroup()
    {
        $sizesMapper = new SizesAdminMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        $sizesList = $sizesMapper->getGroup();
        
        $this->assertTrue(is_array($sizesList));
        $this->assertFalse(empty($sizesList));
        $this->assertTrue(is_object($sizesList[0]));
        $this->assertTrue($sizesList[0] instanceof SizesModel);
        
        $this->assertTrue(property_exists($sizesList[0], 'id'));
        $this->assertTrue(property_exists($sizesList[0], 'size'));
        
        $this->assertFalse(empty($sizesList[0]->id));
        $this->assertFalse(empty($sizesList[0]->size));
    }
    
    /**
     * Тестирует метод SizesAdminMapper::getGroup с учетом категории
     */
    public function testGetGroupCategories()
    {
        \Yii::$app->filters->categories = 'mensfootwear';
        
        $sizesMapper = new SizesAdminMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        $sizesList = $sizesMapper->getGroup();
        
        $this->assertTrue(is_array($sizesList));
        $this->assertFalse(empty($sizesList));
        $this->assertTrue(is_object($sizesList[0]));
        $this->assertTrue($sizesList[0] instanceof SizesModel);
        
        $this->assertTrue(property_exists($sizesList[0], 'id'));
        $this->assertTrue(property_exists($sizesList[0], 'size'));
        
        $this->assertFalse(empty($sizesList[0]->id));
        $this->assertFalse(empty($sizesList[0]->size));
    }
    
    /**
     * Тестирует метод SizesAdminMapper::getGroup с учетом категории и подкатегории
     */
    public function testGetGroupSubcategories()
    {
        \Yii::$app->filters->categories = 'mensfootwear';
        \Yii::$app->filters->subcategory = 'boots';
        
        $sizesMapper = new SizesAdminMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        $sizesList = $sizesMapper->getGroup();
        
        $this->assertTrue(is_array($sizesList));
        $this->assertFalse(empty($sizesList));
        $this->assertTrue(is_object($sizesList[0]));
        $this->assertTrue($sizesList[0] instanceof SizesModel);
        
        $this->assertTrue(property_exists($sizesList[0], 'id'));
        $this->assertTrue(property_exists($sizesList[0], 'size'));
        
        $this->assertFalse(empty($sizesList[0]->id));
        $this->assertFalse(empty($sizesList[0]->size));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}