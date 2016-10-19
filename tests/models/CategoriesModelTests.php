<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\{CategoriesModel,
    ProductsModel,
    SubcategoryModel};

/**
 * Тестирует класс app\models\CategoriesModel
 */
class CategoriesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>'app\tests\sources\fixtures\CategoriesFixture',
                'subcategory'=>'app\tests\sources\fixtures\SubcategoryFixture',
                'products'=>'app\tests\sources\fixtures\ProductsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\CategoriesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\CategoriesModel
     */
    public function testProperties()
    {
        $model = new CategoriesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('seocode', $model->attributes));
        $this->assertTrue(array_key_exists('active', $model->attributes));
    }
    
    /**
     * Тестирует метод CategoriesModel::getSubcategory
     */
    public function testGetSubcategory()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        
        $model = CategoriesModel::find()->where(['categories.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_array($model->subcategory));
        $this->assertFalse(empty($model->subcategory));
        $this->assertTrue(is_object($model->subcategory[0]));
        $this->assertTrue($model->subcategory[0] instanceof SubcategoryModel);
    }
    
    /**
     * Тестирует метод CategoriesModel::getProducts
     */
    public function testGetProducts()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        
        $model = CategoriesModel::find()->where(['categories.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_array($model->products));
        $this->assertFalse(empty($model->products));
        $this->assertTrue(is_object($model->products[0]));
        $this->assertTrue($model->products[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $categoriesQuery = CategoriesModel::find();
        $categoriesQuery->extendSelect(['id', 'name', 'seocode', 'active']);
        
        $queryRaw = clone $categoriesQuery;
        
        $expectedQuery = "SELECT `categories`.`id`, `categories`.`name`, `categories`.`seocode`, `categories`.`active` FROM `categories`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $categoriesQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof CategoriesModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        
        $categoriesQuery = CategoriesModel::find();
        $categoriesQuery->extendSelect(['id', 'name', 'seocode', 'active']);
        $categoriesQuery->where(['categories.seocode'=>$fixture['seocode']]);
        
        $queryRaw = clone $categoriesQuery;
        
        $expectedQuery = sprintf("SELECT `categories`.`id`, `categories`.`name`, `categories`.`seocode`, `categories`.`active` FROM `categories` WHERE `categories`.`seocode`='%s'", $fixture['seocode']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $categoriesQuery->one();
        
        $this->assertTrue($result instanceof CategoriesModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
