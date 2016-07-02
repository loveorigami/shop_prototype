<?php

namespace app\tests\cart;

use app\tests\DbManager;
use app\models\ProductsModel;

/**
 * Тестирует класс app\cart\ShoppingCart
 */
class ShoppingCartTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_name = 'Some name';
    private static $_description = 'Some description';
    private static $_price = 234.78;
    private static $_colorToCart = 1;
    private static $_colorToCart2 = 12;
    private static $_sizeToCart = 3;
    private static $_sizeToCart2 = 1;
    private static $_quantity = 1;
    private static $_quantity2 = 1;
    private static $_categories = 'mensfootwear';
    private static $_subcategory = 'snickers';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
    * Тестирует метод app\cart\ShoppingCart::addProduct
    */
    public function testAddProduct()
    {
        $model = new ProductsModel([
            'id'=>self::$_id,
            'name'=>self::$_name,
            'description'=>self::$_description,
            'price'=>self::$_price,
            'colorToCart'=>self::$_colorToCart,
            'sizeToCart'=>self::$_sizeToCart,
            'quantity'=>self::$_quantity,
            'categories'=>self::$_categories,
            'subcategory'=>self::$_subcategory,
        ]);
        
        $this->assertTrue(empty(\Yii::$app->cart->getProductsArray()));
        
        \Yii::$app->cart->addProduct($model);
        
        $this->assertFalse(empty(\Yii::$app->cart->getProductsArray()));
        
        $productsArray = \Yii::$app->cart->getProductsArray();
        
        $this->assertTrue(is_object($productsArray[0]));
        $this->assertTrue($productsArray[0] instanceof ProductsModel);
        
        $this->assertTrue(property_exists($productsArray[0], 'id'));
        $this->assertTrue(property_exists($productsArray[0], 'name'));
        $this->assertTrue(property_exists($productsArray[0], 'description'));
        $this->assertTrue(property_exists($productsArray[0], 'price'));
        $this->assertTrue(property_exists($productsArray[0], 'colorToCart'));
        $this->assertTrue(property_exists($productsArray[0], 'sizeToCart'));
        $this->assertTrue(property_exists($productsArray[0], 'quantity'));
        $this->assertTrue(property_exists($productsArray[0], 'categories'));
        $this->assertTrue(property_exists($productsArray[0], 'subcategory'));
        
        $this->assertTrue(isset($productsArray[0]->id));
        $this->assertTrue(isset($productsArray[0]->name));
        $this->assertTrue(isset($productsArray[0]->description));
        $this->assertTrue(isset($productsArray[0]->price));
        $this->assertTrue(isset($productsArray[0]->colorToCart));
        $this->assertTrue(isset($productsArray[0]->sizeToCart));
        $this->assertTrue(isset($productsArray[0]->quantity));
        $this->assertTrue(isset($productsArray[0]->categories));
        $this->assertTrue(isset($productsArray[0]->subcategory));
    }
    
    /**
    * Тестирует метод app\cart\ShoppingCart::clearProductsArray
    */
    public function testClearProductsArray()
    {
        $this->assertFalse(empty(\Yii::$app->cart->getProductsArray()));
        
        \Yii::$app->cart->clearProductsArray();
        
        $this->assertTrue(empty(\Yii::$app->cart->getProductsArray()));
    }
    
    /**
    * Тестирует метод app\cart\ShoppingCart::addProduct
    * при повторном добавлении того же продукта
    */
    public function testDoubleAddProduct()
    {
        $this->assertTrue(empty(\Yii::$app->cart->getProductsArray()));
        
        $model = new ProductsModel([
            'id'=>self::$_id,
            'name'=>self::$_name,
            'description'=>self::$_description,
            'price'=>self::$_price,
            'colorToCart'=>self::$_colorToCart,
            'sizeToCart'=>self::$_sizeToCart,
            'quantity'=>self::$_quantity,
            'categories'=>self::$_categories,
            'subcategory'=>self::$_subcategory,
        ]);
        
        \Yii::$app->cart->addProduct($model);
        
        $this->assertEquals(1, count(\Yii::$app->cart->getProductsArray()));
        $this->assertEquals(1, \Yii::$app->cart->getProductsArray()[0]->quantity);
        
        \Yii::$app->cart->addProduct($model);
        
        $this->assertEquals(1, count(\Yii::$app->cart->getProductsArray()));
        $this->assertEquals(2, \Yii::$app->cart->getProductsArray()[0]->quantity);
    }
    
    /**
    * Тестирует метод app\cart\ShoppingCart::addProduct
    * при повторном добавлении того же продукта c другими характеристиками
    */
    public function testDoubleDiffAddProduct()
    {
        \Yii::$app->cart->clearProductsArray();
        
        $this->assertTrue(empty(\Yii::$app->cart->getProductsArray()));
        
        $model = new ProductsModel([
            'id'=>self::$_id,
            'name'=>self::$_name,
            'description'=>self::$_description,
            'price'=>self::$_price,
            'colorToCart'=>self::$_colorToCart,
            'sizeToCart'=>self::$_sizeToCart,
            'quantity'=>self::$_quantity,
            'categories'=>self::$_categories,
            'subcategory'=>self::$_subcategory,
        ]);
        
        \Yii::$app->cart->addProduct($model);
        
        $this->assertEquals(1, count(\Yii::$app->cart->getProductsArray()));
        
        $model = new ProductsModel([
            'id'=>self::$_id,
            'name'=>self::$_name,
            'description'=>self::$_description,
            'price'=>self::$_price,
            'colorToCart'=>self::$_colorToCart + 2,
            'sizeToCart'=>self::$_sizeToCart,
            'quantity'=>self::$_quantity,
            'categories'=>self::$_categories,
            'subcategory'=>self::$_subcategory,
        ]);
        
        \Yii::$app->cart->addProduct($model);
        
        $this->assertEquals(2, count(\Yii::$app->cart->getProductsArray()));
    }
    
    /**
    * Тестирует метод app\cart\ShoppingCart::removeProduct
    */
    public function testRemoveProduct()
    {
        \Yii::$app->cart->clearProductsArray();
        
        $model = new ProductsModel([
            'id'=>self::$_id,
            'name'=>self::$_name,
            'description'=>self::$_description,
            'price'=>self::$_price,
            'colorToCart'=>self::$_colorToCart,
            'sizeToCart'=>self::$_sizeToCart,
            'quantity'=>self::$_quantity,
            'categories'=>self::$_categories,
            'subcategory'=>self::$_subcategory,
        ]);
        
        $this->assertTrue(empty(\Yii::$app->cart->getProductsArray()));
        
        \Yii::$app->cart->addProduct($model);
        
        $this->assertFalse(empty(\Yii::$app->cart->getProductsArray()));
        
        \Yii::$app->cart->removeProduct($model);
        
        $this->assertTrue(empty(\Yii::$app->cart->getProductsArray()));
    }
    
    /**
    * Тестирует метод app\cart\ShoppingCart::updateProduct
    */
    public function testUpdateProduct()
    {
        \Yii::$app->cart->clearProductsArray();
        
        $model = new ProductsModel([
            'id'=>self::$_id,
            'name'=>self::$_name,
            'description'=>self::$_description,
            'price'=>self::$_price,
            'colorToCart'=>self::$_colorToCart,
            'sizeToCart'=>self::$_sizeToCart,
            'quantity'=>self::$_quantity,
            'categories'=>self::$_categories,
            'subcategory'=>self::$_subcategory,
        ]);
        
        $model2 = new ProductsModel([
            'id'=>self::$_id,
            'name'=>self::$_name,
            'description'=>self::$_description,
            'price'=>self::$_price,
            'colorToCart'=>self::$_colorToCart2,
            'sizeToCart'=>self::$_sizeToCart2,
            'quantity'=>self::$_quantity2,
            'categories'=>self::$_categories,
            'subcategory'=>self::$_subcategory,
        ]);
        
        $this->assertTrue(empty(\Yii::$app->cart->getProductsArray()));
        
        \Yii::$app->cart->addProduct($model);
        
        $this->assertFalse(empty(\Yii::$app->cart->getProductsArray()));
        
        $productsArray = \Yii::$app->cart->getProductsArray();
        
        $this->assertEquals(self::$_id, $productsArray[0]->id);
        $this->assertEquals(self::$_name, $productsArray[0]->name);
        $this->assertEquals(self::$_description, $productsArray[0]->description);
        $this->assertEquals(self::$_price, $productsArray[0]->price);
        $this->assertEquals(self::$_colorToCart, $productsArray[0]->colorToCart);
        $this->assertEquals(self::$_sizeToCart, $productsArray[0]->sizeToCart);
        $this->assertEquals(self::$_quantity, $productsArray[0]->quantity);
        $this->assertEquals(self::$_categories, $productsArray[0]->categories);
        $this->assertEquals(self::$_subcategory, $productsArray[0]->subcategory);
        
        \Yii::$app->cart->updateProduct($model2);
        
        $this->assertEquals(self::$_id, $productsArray[0]->id);
        $this->assertEquals(self::$_name, $productsArray[0]->name);
        $this->assertEquals(self::$_description, $productsArray[0]->description);
        $this->assertEquals(self::$_price, $productsArray[0]->price);
        $this->assertEquals(self::$_colorToCart2, $productsArray[0]->colorToCart);
        $this->assertEquals(self::$_sizeToCart2, $productsArray[0]->sizeToCart);
        $this->assertEquals(self::$_quantity2, $productsArray[0]->quantity);
        $this->assertEquals(self::$_categories, $productsArray[0]->categories);
        $this->assertEquals(self::$_subcategory, $productsArray[0]->subcategory);
    }
    
    /**
    * Тестирует метод app\cart\ShoppingCart::setProductsArray
    */
    public function testSetProductsArray()
    {
        \Yii::$app->cart->clearProductsArray();
        
        $model = new ProductsModel([
            'id'=>self::$_id,
            'name'=>self::$_name,
            'description'=>self::$_description,
            'price'=>self::$_price,
            'colorToCart'=>self::$_colorToCart,
            'sizeToCart'=>self::$_sizeToCart,
            'quantity'=>self::$_quantity,
            'categories'=>self::$_categories,
            'subcategory'=>self::$_subcategory,
        ]);
        
        $model2 = new ProductsModel([
            'id'=>self::$_id + 1,
            'name'=>self::$_name,
            'description'=>self::$_description,
            'price'=>self::$_price,
            'colorToCart'=>self::$_colorToCart2,
            'sizeToCart'=>self::$_sizeToCart2,
            'quantity'=>self::$_quantity2,
            'categories'=>self::$_categories,
            'subcategory'=>self::$_subcategory,
        ]);
        
        $this->assertTrue(empty(\Yii::$app->cart->getProductsArray()));
        
        \Yii::$app->cart->setProductsArray([$model, $model2]);
        
        $this->assertFalse(empty(\Yii::$app->cart->getProductsArray()));
        $this->assertEquals(2, count(\Yii::$app->cart->getProductsArray()));
    }
    
    /**
    * Тестирует метод app\cart\ShoppingCart::getShortData
    */
    public function testGetShortData()
    {
        \Yii::$app->cart->clearProductsArray();
        
        $model = new ProductsModel([
            'id'=>self::$_id,
            'name'=>self::$_name,
            'description'=>self::$_description,
            'price'=>self::$_price,
            'colorToCart'=>self::$_colorToCart,
            'sizeToCart'=>self::$_sizeToCart,
            'quantity'=>self::$_quantity,
            'categories'=>self::$_categories,
            'subcategory'=>self::$_subcategory,
        ]);
        
        \Yii::$app->cart->addProduct($model);
        
        \Yii::$app->cart->getShortData();
        
        $this->assertEquals((self::$_price * self::$_quantity), \Yii::$app->cart->getTotalCost());
        $this->assertEquals(self::$_quantity, \Yii::$app->cart->getTotalProducts());
        
        $model2 = new ProductsModel([
            'id'=>self::$_id + 1,
            'name'=>self::$_name,
            'description'=>self::$_description,
            'price'=>self::$_price,
            'colorToCart'=>self::$_colorToCart2,
            'sizeToCart'=>self::$_sizeToCart2,
            'quantity'=>self::$_quantity2,
            'categories'=>self::$_categories,
            'subcategory'=>self::$_subcategory,
        ]);
        
        \Yii::$app->cart->addProduct($model2);
        
        \Yii::$app->cart->getShortData();
        
        $this->assertEquals(self::$_price * (self::$_quantity + self::$_quantity2), \Yii::$app->cart->getTotalCost());
        $this->assertEquals((self::$_quantity + self::$_quantity2), \Yii::$app->cart->getTotalProducts());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
