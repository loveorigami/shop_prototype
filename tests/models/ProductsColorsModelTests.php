<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\{ColorsModel,
    ProductsColorsModel,
    ProductsModel};

/**
 * Тестирует класс app\models\ProductsColorsModel
 */
class ProductsColorsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products_colors'=>'app\tests\sources\fixtures\ProductsColorsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ProductsColorsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ProductsColorsModel
     */
    public function testProperties()
    {
        $model = new ProductsColorsModel();
        
        $this->assertTrue(array_key_exists('id_product', $model->attributes));
        $this->assertTrue(array_key_exists('id_color', $model->attributes));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $productsColorsQuery = ProductsColorsModel::find();
        $productsColorsQuery->extendSelect(['id_product', 'id_color']);
        
        $queryRaw = clone $productsColorsQuery;
        
        $expectedQuery = "SELECT `products_colors`.`id_product`, `products_colors`.`id_color` FROM `products_colors`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsColorsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsColorsModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->products_colors['product_color_1'];
        
        $productsColorsQuery = ProductsColorsModel::find();
        $productsColorsQuery->extendSelect(['id_product', 'id_color']);
        $productsColorsQuery->where(['products_colors.id_product'=>$fixture['id_product']]);
        
        $queryRaw = clone $productsColorsQuery;
        
        $expectedQuery = sprintf("SELECT `products_colors`.`id_product`, `products_colors`.`id_color` FROM `products_colors` WHERE `products_colors`.`id_product`=%d", $fixture['id_product']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsColorsQuery->one();
        
        $this->assertTrue($result instanceof ProductsColorsModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->products_colors['product_color_1'];
        $fixture2 = self::$_dbClass->products_colors['product_color_2'];
        
        $productsColorsQuery = ProductsColorsModel::find();
        $productsColorsQuery->extendSelect(['id_product', 'id_color']);
        $productsColorsArray = $productsColorsQuery->allMap('id_product', 'id_color');
        
        $this->assertFalse(empty($productsColorsArray));
        $this->assertTrue(array_key_exists($fixture['id_product'], $productsColorsArray));
        $this->assertTrue(array_key_exists($fixture2['id_product'], $productsColorsArray));
        $this->assertTrue(in_array($fixture['id_color'], $productsColorsArray));
        $this->assertTrue(in_array($fixture2['id_color'], $productsColorsArray));
    }
    
    /**
     * Тестирует метод ProductsColorsModel::batchInsert
     */
    public function testBatchInsert()
    {
        $fixture_1 = self::$_dbClass->products_colors['product_color_1'];
        $fixture_2 = self::$_dbClass->products_colors['product_color_2'];
        
        $productsModel = new ProductsModel(['id'=>$fixture_1['id_product']]);
        $colorsModel = new ColorsModel(['id'=>[$fixture_1['id_color'], $fixture_2['id_color']]]);
        
        \Yii::$app->db->createCommand('DELETE FROM {{products_colors}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products_colors}}')->queryAll()));
        
        $result = ProductsColorsModel::batchInsert($productsModel, $colorsModel);
        $this->assertTrue(is_int($result));
        $this->assertEquals(2, $result);
        
        $this->assertFalse(empty($result = \Yii::$app->db->createCommand('SELECT * FROM {{products_colors}}')->queryAll()));
        $this->assertEquals(2, count($result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
