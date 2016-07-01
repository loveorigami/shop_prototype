<?php

namespace app\test\models;

use app\tests\DbManager;
use app\models\ProductsModel;
use app\models\ColorsModel;
use app\models\SizesModel;
use app\models\CommentsModel;

/**
 * Тестирует ProductsModel
 */
class ProductsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_date = '1462453595';
    private static $_code = 'GH56tg';
    private static $_name = 'Some Name';
    private static $_description = 'Some description';
    private static $_price = 123.45;
    private static $_images = 'images/';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_quantity = 1;
    private static $_sizeToCart = 23;
    private static $_colorToCart = 2;
    private static $_color = 'some';
    private static $_size = 'some';
    private static $_text = 'Some text';
    private static $_email = 'some@some.com';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        self::$_reflectionClass = new \ReflectionClass('app\models\ProductsModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_colors}} SET [[id_products]]=:id_products, [[id_colors]]=:id_colors');
        $command->bindValues([':id_products'=>self::$_id, ':id_colors'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_sizes}} SET [[id_products]]=:id_products, [[id_sizes]]=:id_sizes');
        $command->bindValues([':id_products'=>self::$_id, ':id_sizes'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id + 1, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_colors}} SET [[id_products]]=:id_products, [[id_colors]]=:id_colors');
        $command->bindValues([':id_products'=>self::$_id + 1, ':id_colors'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_sizes}} SET [[id_products]]=:id_products, [[id_sizes]]=:id_sizes');
        $command->bindValues([':id_products'=>self::$_id + 1, ':id_sizes'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{related_products}} SET [[id_products]]=:id_products, [[id_related_products]]=:id_related_products');
        $command->bindValues([':id_products'=>self::$_id, ':id_related_products'=>self::$_id + 1]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{emails}} SET [[id]]=:id, [[email]]=:email');
        $command->bindValues([':id'=>self::$_id, ':email'=>self::$_email]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{comments}} SET [[id]]=:id, [[text]]=:text, [[name]]=:name, [[id_emails]]=:id_emails, [[id_products]]=:id_products, [[active]]=:active');
        $command->bindValues([':id'=>self::$_id, ':text'=>self::$_text, ':name'=>self::$_name, ':id_emails'=>self::$_id, ':id_products'=>self::$_id, ':active'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств, констант и методов
     */
    public function testProperties()
    {
        $model = new ProductsModel();
        
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_LIST_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM_TO_CART'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM_FOR_REMOVE'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM_FOR_CLEAR_CART'));
        
        $this->assertTrue(property_exists($model, 'id'));
        $this->assertTrue(property_exists($model, 'date'));
        $this->assertTrue(property_exists($model, 'code'));
        $this->assertTrue(property_exists($model, 'name'));
        $this->assertTrue(property_exists($model, 'description'));
        $this->assertTrue(property_exists($model, 'price'));
        $this->assertTrue(property_exists($model, 'images'));
        $this->assertTrue(property_exists($model, 'id_categories'));
        $this->assertTrue(property_exists($model, 'id_subcategory'));
        $this->assertTrue(property_exists($model, 'categories'));
        $this->assertTrue(property_exists($model, 'subcategory'));
        $this->assertTrue(property_exists($model, 'colorToCart'));
        $this->assertTrue(property_exists($model, 'sizeToCart'));
        $this->assertTrue(property_exists($model, 'quantity'));
        $this->assertTrue(property_exists($model, '_colors'));
        $this->assertTrue(property_exists($model, '_sizes'));
        $this->assertTrue(property_exists($model, '_similar'));
        $this->assertTrue(property_exists($model, '_related'));
        $this->assertTrue(property_exists($model, '_comments'));
        
        $this->assertTrue(method_exists($model, 'getColors'));
        $this->assertTrue(method_exists($model, 'getSizes'));
        $this->assertTrue(method_exists($model, 'getSimilar'));
        $this->assertTrue(method_exists($model, 'getRelated'));
        $this->assertTrue(method_exists($model, 'getComments'));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_LIST_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'date'=>self::$_date, 'code'=>self::$_code, 'name'=>self::$_name, 'description'=>self::$_description, 'price'=>self::$_price, 'images'=>self::$_images, 'categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode, 'colorToCart'=>self::$_colorToCart];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->date));
        $this->assertFalse(empty($model->code));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->description));
        $this->assertFalse(empty($model->price));
        $this->assertFalse(empty($model->images));
        $this->assertFalse(empty($model->categories));
        $this->assertFalse(empty($model->subcategory));
        $this->assertTrue(empty($model->colorToCart));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_date, $model->date);
        $this->assertEquals(self::$_code, $model->code);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_description, $model->description);
        $this->assertEquals(self::$_price, $model->price);
        $this->assertEquals(self::$_images, $model->images);
        $this->assertEquals(self::$_categorySeocode, $model->categories);
        $this->assertEquals(self::$_subcategorySeocode, $model->subcategory);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
        $model->attributes = ['id'=>self::$_id, 'code'=>self::$_code, 'name'=>self::$_name, 'description'=>self::$_description, 'price'=>self::$_price, 'colorToCart'=>self::$_colorToCart, 'sizeToCart'=>self::$_sizeToCart, 'quantity'=>self::$_quantity, 'categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode, 'date'=>self::$_date];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->code));
        $this->assertFalse(empty($model->name));
        $this->assertFalse(empty($model->description));
        $this->assertFalse(empty($model->price));
        $this->assertFalse(empty($model->colorToCart));
        $this->assertFalse(empty($model->sizeToCart));
        $this->assertFalse(empty($model->quantity));
        $this->assertFalse(empty($model->categories));
        $this->assertFalse(empty($model->subcategory));
        $this->assertTrue(empty($model->date));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_code, $model->code);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_description, $model->description);
        $this->assertEquals(self::$_price, $model->price);
        $this->assertEquals(self::$_colorToCart, $model->colorToCart);
        $this->assertEquals(self::$_sizeToCart, $model->sizeToCart);
        $this->assertEquals(self::$_quantity, $model->quantity);
        $this->assertEquals(self::$_categorySeocode, $model->categories);
        $this->assertEquals(self::$_subcategorySeocode, $model->subcategory);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_REMOVE]);
        $model->attributes = ['id'=>self::$_id, 'code'=>self::$_code, 'name'=>self::$_name];
        
        $this->assertFalse(empty($model->id));
        $this->assertTrue(empty($model->code));
        $this->assertTrue(empty($model->name));
        
        $this->assertEquals(self::$_id, $model->id);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_CLEAR_CART]);
        $model->attributes = ['id'=>self::$_id, 'categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode, 'code'=>self::$_code];
        
        $this->assertFalse(empty($model->id));
        $this->assertFalse(empty($model->categories));
        $this->assertFalse(empty($model->subcategory));
        $this->assertTrue(empty($model->code));
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_categorySeocode, $model->categories);
        $this->assertEquals(self::$_subcategorySeocode, $model->subcategory);
    }
    
    /**
     * Тестирует метод ProductsModel::getColors
     */
    public function testGetColors()
    {
        $model = new ProductsModel();
        $model->id = self::$_id;
        
        $colorsArray = $model->colors;
        
        $this->assertTrue(is_array($colorsArray));
        $this->assertFalse(empty($colorsArray));
        $this->assertTrue(is_object($colorsArray[0]));
        $this->assertTrue($colorsArray[0] instanceof ColorsModel);
    }
    
    /**
     * Тестирует выброс исключения в методе ProductsModel::getColors
     * @expectedException ErrorException
     */
    public function testExcGetColors()
    {
        $model = new ProductsModel();
        //$model->id = self::$_id;
        
        $model->colors;
    }
    
    /**
     * Тестирует метод ProductsModel::getSizes
     */
    public function testGetSizes()
    {
        $model = new ProductsModel();
        $model->id = self::$_id;
        
        $sizesArray = $model->sizes;
        
        $this->assertTrue(is_array($sizesArray));
        $this->assertFalse(empty($sizesArray));
        $this->assertTrue(is_object($sizesArray[0]));
        $this->assertTrue($sizesArray[0] instanceof SizesModel);
    }
    
    /**
     * Тестирует выброс исключения в методе ProductsModel::getSizes
     * @expectedException ErrorException
     */
    public function testExcGetSizes()
    {
        $model = new ProductsModel();
        //$model->id = self::$_id;
        
        $model->sizes;
    }
    
    /**
     * Тестирует метод ProductsModel::getSimilar
     */
    public function testGetSimilar()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $model = new ProductsModel();
        $model->id = self::$_id;
        
        $similarArray = $model->similar;
        
        $this->assertTrue(is_array($similarArray));
        $this->assertFalse(empty($similarArray));
        $this->assertTrue(is_object($similarArray[0]));
        $this->assertTrue($similarArray[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует выброс исключения в методе ProductsModel::getSimilar
     * @expectedException ErrorException
     */
    public function testExcGetSimilar()
    {
        $model = new ProductsModel();
        //$model->id = self::$_id;
        
        $model->similar;
    }
    
    /**
     * Тестирует метод ProductsModel::getRelated
     */
    public function testGetRelated()
    {
        $model = new ProductsModel();
        $model->id = self::$_id;
        
        $relatedArray = $model->related;
        
        $this->assertTrue(is_array($relatedArray));
        $this->assertFalse(empty($relatedArray));
        $this->assertTrue(is_object($relatedArray[0]));
        $this->assertTrue($relatedArray[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует выброс исключения в методе ProductsModel::getRelated
     * @expectedException ErrorException
     */
    public function testExcGetRelated()
    {
        $model = new ProductsModel();
        //$model->id = self::$_id;
        
        $model->related;
    }
    
    /**
     * Тестирует метод ProductsModel::getComments
     */
    public function testGetComments()
    {
        $model = new ProductsModel();
        $model->id = self::$_id;
        
        $commentsArray = $model->comments;
        
        $this->assertTrue(is_array($commentsArray));
        $this->assertFalse(empty($commentsArray));
        $this->assertTrue(is_object($commentsArray[0]));
        $this->assertTrue($commentsArray[0] instanceof CommentsModel);
    }
    
    /**
     * Тестирует выброс исключения в методе ProductsModel::getComments
     * @expectedException ErrorException
     */
    public function testExcGetComments()
    {
        $model = new ProductsModel();
        //$model->id = self::$_id;
        
        $model->comments;
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}