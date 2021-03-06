<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\{CategoriesModel,
    ProductsModel,
    SubcategoryModel};
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    ProductsFixture,
    SubcategoryFixture};

/**
 * Тестирует класс CategoriesModel
 */
class CategoriesModelTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'products'=>ProductsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CategoriesModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CategoriesModel::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        $this->assertTrue($reflection->hasConstant('EDIT'));
        
        $model = new CategoriesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('name', $model->attributes);
        $this->assertArrayHasKey('seocode', $model->attributes);
        $this->assertArrayHasKey('active', $model->attributes);
    }
    
    /**
     * Тестирует метод CategoriesModel::tableName
     */
    public function testTableName()
    {
        $result = CategoriesModel::tableName();
        
        $this->assertSame('categories', $result);
    }
    
    /**
     * Тестирует метод CategoriesModel::scenarios
     */
    public function testScenarios()
    {
        $model = new CategoriesModel(['scenario'=>CategoriesModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        
        $this->assertEquals(23, $model->id);
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::CREATE]);
        $model->attributes = [
            'name'=>'name',
            'seocode'=>'seocode',
            'active'=>true,
        ];
        
        $this->assertEquals('name', $model->name);
        $this->assertEquals('seocode', $model->seocode);
        $this->assertEquals(true, $model->active);
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::EDIT]);
        $model->attributes = [
            'id'=>23
        ];
        
        $this->assertEquals(23, $model->id);
    }
    
    /**
     * Тестирует метод CategoriesModel::rules
     */
    public function testRules()
    {
        $model = new CategoriesModel(['scenario'=>CategoriesModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::CREATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(2, $model->errors);
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::CREATE]);
        $model->attributes = [
            'name'=>'name',
            'seocode'=>'seocode',
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        $this->assertSame(0, $model->active);
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::EDIT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::EDIT]);
        $model->attributes = [
            'id'=>23
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        $this->assertSame(0, $model->active);
    }
    
    /**
     * Тестирует метод CategoriesModel::getSubcategory
     */
    public function testGetSubcategory()
    {
        $model = new CategoriesModel();
        $model->id = 1;
        
        $result = $model->subcategory;
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(SubcategoryModel::class, $item);
        }
    }
    
    /**
     * Тестирует метод CategoriesModel::getProducts
     */
    public function testGetProducts()
    {
        $model = new CategoriesModel();
        $model->id = 1;
        
        $result = $model->products;
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ProductsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
