<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\tests\MockModel;
use app\mappers\ProductsByCodeMapper;
use app\models\ProductsModel;

/**
 * Тестирует класс app\mappers\ProductsByCodeMapper
 */
class ProductsByCodeMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 231;
    private static $_date = 1462453595;
    private static $_code = 'YU-6709';
    private static $_name = 'name';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_images = 'images';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':date'=>self::$_date, ':code'=>self::$_code, ':name'=>self::$_name, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод ProductsByCodeMapper::getOneFromGroup
     */
    public function testGetOneFromGroup()
    {
        $productsByCodeMapper = new ProductsByCodeMapper([
            'tableName'=>'products',
            'fields'=>['id', 'date', 'code', 'name', 'description', 'price', 'images', 'id_categories', 'id_subcategory'],
            'model'=>new ProductsModel([
                'code'=>self::$_code,
            ]),
        ]);
        $productsModel = $productsByCodeMapper->getOneFromGroup();
        
        print_r($productsModel);
        
        $this->assertTrue(is_object($productsModel));
        $this->assertTrue($productsModel instanceof ProductsModel);
        
        $this->assertTrue(property_exists($productsModel, 'date'));
        $this->assertTrue(property_exists($productsModel, 'code'));
        $this->assertTrue(property_exists($productsModel, 'name'));
        $this->assertTrue(property_exists($productsModel, 'description'));
        $this->assertTrue(property_exists($productsModel, 'price'));
        $this->assertTrue(property_exists($productsModel, 'images'));
        $this->assertTrue(property_exists($productsModel, 'id_categories'));
        $this->assertTrue(property_exists($productsModel, 'id_subcategory'));
        
        $this->assertFalse(empty($productsModel->id));
        $this->assertFalse(empty($productsModel->date));
        $this->assertFalse(empty($productsModel->code));
        $this->assertFalse(empty($productsModel->name));
        $this->assertFalse(empty($productsModel->description));
        $this->assertFalse(empty($productsModel->price));
        $this->assertFalse(empty($productsModel->images));
        $this->assertFalse(empty($productsModel->id_categories));
        $this->assertFalse(empty($productsModel->id_subcategory));
        
        $this->assertEquals(self::$_id, $productsModel->id);
        $this->assertEquals(self::$_date, $productsModel->date);
        $this->assertEquals(self::$_code, $productsModel->code);
        $this->assertEquals(self::$_name, $productsModel->name);
        $this->assertEquals(self::$_description, $productsModel->description);
        $this->assertEquals(self::$_price, $productsModel->price);
        $this->assertEquals(self::$_images, $productsModel->images);
        $this->assertEquals(self::$_id, $productsModel->id_categories);
        $this->assertEquals(self::$_id, $productsModel->id_subcategory);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
