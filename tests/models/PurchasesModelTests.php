<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\PurchasesModel;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ColorsFixture,
    ProductsFixture,
    SizesFixture};
use app\models\{ColorsModel,
    ProductsModel,
    SizesModel};

/**
 * Тестирует класс PurchasesModel
 */
class PurchasesModelTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'colors'=>ColorsFixture::class,
                'sizes'=>SizesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PurchasesModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchasesModel::class);
        
        $this->assertTrue($reflection->hasConstant('SESSION'));
        $this->assertTrue($reflection->hasConstant('UPDATE'));
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $model = new PurchasesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('id_user', $model->attributes);
        $this->assertArrayHasKey('id_name', $model->attributes);
        $this->assertArrayHasKey('id_surname', $model->attributes);
        $this->assertArrayHasKey('id_email', $model->attributes);
        $this->assertArrayHasKey('id_phone', $model->attributes);
        $this->assertArrayHasKey('id_address', $model->attributes);
        $this->assertArrayHasKey('id_city', $model->attributes);
        $this->assertArrayHasKey('id_country', $model->attributes);
        $this->assertArrayHasKey('id_postcode', $model->attributes);
        $this->assertArrayHasKey('id_product', $model->attributes); 
        $this->assertArrayHasKey('quantity', $model->attributes); 
        $this->assertArrayHasKey('id_color', $model->attributes); 
        $this->assertArrayHasKey('id_size', $model->attributes);
        $this->assertArrayHasKey('price', $model->attributes); 
        $this->assertArrayHasKey('id_delivery', $model->attributes); 
        $this->assertArrayHasKey('id_payment', $model->attributes); 
        $this->assertArrayHasKey('received', $model->attributes); 
        $this->assertArrayHasKey('received_date', $model->attributes);
        $this->assertArrayHasKey('processed', $model->attributes); 
        $this->assertArrayHasKey('canceled', $model->attributes); 
        $this->assertArrayHasKey('shipped', $model->attributes);
    }
    
    /**
     * Тестирует метод PurchasesModel::tableName
     */
    public function testTableName()
    {
        $result = PurchasesModel::tableName();
        
        $this->assertSame('purchases', $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::scenarios
     */
    public function testScenarios()
    {
        $model = new PurchasesModel(['scenario'=>PurchasesModel::SESSION]);
        $model->attributes = [
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
            'price'=>245.98, 
        ];
        
        $result = $model->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id_product', $result);
        $this->assertSame(34, $result['id_product']);
        $this->assertArrayHasKey('quantity', $result);
        $this->assertSame(2, $result['quantity']);
        $this->assertArrayHasKey('id_color', $result);
        $this->assertSame(4, $result['id_color']);
        $this->assertArrayHasKey('id_size', $result);
        $this->assertSame(2, $result['id_size']);
        $this->assertArrayHasKey('price', $result);
        $this->assertSame(245.98, $result['price']);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::UPDATE]);
        $model->attributes = [
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
        ];
        
        $result = $model->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id_product', $result);
        $this->assertSame(34, $result['id_product']);
        $this->assertArrayHasKey('quantity', $result);
        $this->assertSame(2, $result['quantity']);
        $this->assertArrayHasKey('id_color', $result);
        $this->assertSame(4, $result['id_color']);
        $this->assertArrayHasKey('id_size', $result);
        $this->assertSame(2, $result['id_size']);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::DELETE]);
        $model->attributes = [
            'id_product'=>2, 
        ];
        
        $result = $model->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('id_product', $result);
        $this->assertSame(2, $result['id_product']);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::SAVE]);
        $model->attributes = [
            'id_user'=>2,
            'id_name'=>3,
            'id_surname'=>1,
            'id_email'=>12,
            'id_phone'=>4,
            'id_address'=>2,
            'id_city'=>6,
            'id_country'=>7,
            'id_postcode'=>2,
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
            'price'=>245.98, 
            'id_delivery'=>2, 
            'id_payment'=>1, 
            'received'=>1, 
            'received_date'=>1458471063,
        ];
        
        $result = $model->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('id_user', $result);
        $this->assertSame(2, $result['id_user']);
        $this->assertArrayHasKey('id_name', $result);
        $this->assertSame(3, $result['id_name']);
        $this->assertArrayHasKey('id_surname', $result);
        $this->assertSame(1, $result['id_surname']);
        $this->assertArrayHasKey('id_email', $result);
        $this->assertSame(12, $result['id_email']);
        $this->assertArrayHasKey('id_phone', $result);
        $this->assertSame(4, $result['id_phone']);
        $this->assertArrayHasKey('id_address', $result);
        $this->assertSame(2, $result['id_address']);
        $this->assertArrayHasKey('id_city', $result);
        $this->assertSame(6, $result['id_city']);
        $this->assertArrayHasKey('id_country', $result);
        $this->assertSame(7, $result['id_country']);
        $this->assertArrayHasKey('id_postcode', $result);
        $this->assertSame(2, $result['id_postcode']);
        $this->assertArrayHasKey('id_product', $result);
        $this->assertSame(34, $result['id_product']);
        $this->assertArrayHasKey('quantity', $result);
        $this->assertSame(2, $result['quantity']);
        $this->assertArrayHasKey('id_color', $result);
        $this->assertSame(4, $result['id_color']);
        $this->assertArrayHasKey('id_size', $result);
        $this->assertSame(2, $result['id_size']);
        $this->assertArrayHasKey('price', $result);
        $this->assertSame(245.98, $result['price']);
        $this->assertArrayHasKey('id_delivery', $result);
        $this->assertSame(2, $result['id_delivery']);
        $this->assertArrayHasKey('id_payment', $result);
        $this->assertSame(1, $result['id_payment']);
        $this->assertArrayHasKey('received', $result);
        $this->assertSame(1, $result['received']);
        $this->assertArrayHasKey('received_date', $result);
        $this->assertSame(1458471063, $result['received_date']);
        
        /*$model = new PurchasesModel(['scenario'=>PurchasesModel::SESSION]);
        $model->attributes = [
            'id'=>1,
            'id_user'=>2,
            'id_name'=>3,
            'id_surname'=>1,
            'id_email'=>12,
            'id_phone'=>4,
            'id_address'=>2,
            'id_city'=>6,
            'id_country'=>7,
            'id_postcode'=>2,
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
            'price'=>245.98, 
            'id_delivery'=>2, 
            'id_payment'=>1, 
            'received'=>1, 
            'received_date'=>1458471063,
            'processed'=>1, 
            'canceled'=>0, 
            'shipped'=>0,
        ];
        
        $result = $model->toArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('id', $result);
        $this->assertSame(1, $result['id']);
        $this->assertArrayHasKey('id_user', $result);
        $this->assertSame(2, $result['id_user']);
        $this->assertArrayHasKey('id_name', $result);
        $this->assertSame(3, $result['id_name']);
        $this->assertArrayHasKey('id_surname', $result);
        $this->assertSame(1, $result['id_surname']);
        $this->assertArrayHasKey('id_email', $result);
        $this->assertSame(12, $result['id_email']);
        $this->assertArrayHasKey('id_phone', $result);
        $this->assertSame(4, $result['id_phone']);
        $this->assertArrayHasKey('id_address', $result);
        $this->assertSame(2, $result['id_address']);
        $this->assertArrayHasKey('id_city', $result);
        $this->assertSame(6, $result['id_city']);
        $this->assertArrayHasKey('id_country', $result);
        $this->assertSame(7, $result['id_country']);
        $this->assertArrayHasKey('id_postcode', $result);
        $this->assertSame(2, $result['id_postcode']);
        $this->assertArrayHasKey('id_product', $result);
        $this->assertSame(34, $result['id_product']);
        $this->assertArrayHasKey('quantity', $result);
        $this->assertSame(2, $result['quantity']);
        $this->assertArrayHasKey('id_color', $result);
        $this->assertSame(4, $result['id_color']);
        $this->assertArrayHasKey('id_size', $result);
        $this->assertSame(2, $result['id_size']);
        $this->assertArrayHasKey('price', $result);
        $this->assertSame(245.98, $result['price']);
        $this->assertArrayHasKey('id_delivery', $result);
        $this->assertSame(2, $result['id_delivery']);
        $this->assertArrayHasKey('id_payment', $result);
        $this->assertSame(1, $result['id_payment']);
        $this->assertArrayHasKey('received', $result);
        $this->assertSame(1, $result['received']);
        $this->assertArrayHasKey('received_date', $result);
        $this->assertSame(1458471063, $result['received_date']);
        $this->assertArrayHasKey('processed', $result);
        $this->assertSame(1, $result['processed']);
        $this->assertArrayHasKey('canceled', $result);
        $this->assertSame(0, $result['canceled']);
        $this->assertArrayHasKey('shipped', $result);
        $this->assertSame(0, $result['shipped']);*/
    }
    
    /**
     * Тестирует метод PurchasesModel::rules
     */
    public function testRules()
    {
        $model = new PurchasesModel(['scenario'=>PurchasesModel::SESSION]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(5, $model->errors);
        $this->assertArrayHasKey('id_product', $model->errors);
        $this->assertArrayHasKey('quantity', $model->errors);
        $this->assertArrayHasKey('id_color', $model->errors);
        $this->assertArrayHasKey('id_size', $model->errors);
        $this->assertArrayHasKey('price', $model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::SESSION]);
        $model->attributes = [
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
            'price'=>245.98, 
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::UPDATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(4, $model->errors);
        $this->assertArrayHasKey('id_product', $model->errors);
        $this->assertArrayHasKey('quantity', $model->errors);
        $this->assertArrayHasKey('id_color', $model->errors);
        $this->assertArrayHasKey('id_size', $model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::UPDATE]);
        $model->attributes = [
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(1, $model->errors);
        $this->assertArrayHasKey('id_product', $model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::DELETE]);
        $model->attributes = [
            'id_product'=>34, 
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(17, $model->errors);
        $this->assertArrayHasKey('id_name', $model->errors);
        $this->assertArrayHasKey('id_surname', $model->errors);
        $this->assertArrayHasKey('id_email', $model->errors);
        $this->assertArrayHasKey('id_phone', $model->errors);
        $this->assertArrayHasKey('id_address', $model->errors);
        $this->assertArrayHasKey('id_city', $model->errors);
        $this->assertArrayHasKey('id_country', $model->errors);
        $this->assertArrayHasKey('id_postcode', $model->errors);
        $this->assertArrayHasKey('id_product', $model->errors);
        $this->assertArrayHasKey('quantity', $model->errors);
        $this->assertArrayHasKey('id_color', $model->errors);
        $this->assertArrayHasKey('id_size', $model->errors);
        $this->assertArrayHasKey('price', $model->errors);
        $this->assertArrayHasKey('id_delivery', $model->errors);
        $this->assertArrayHasKey('id_payment', $model->errors);
        $this->assertArrayHasKey('received', $model->errors);
        $this->assertArrayHasKey('received_date', $model->errors);
        
        $model = new PurchasesModel(['scenario'=>PurchasesModel::SAVE]);
        $model->attributes = [
            'id_user'=>2,
            'id_name'=>3,
            'id_surname'=>1,
            'id_email'=>12,
            'id_phone'=>4,
            'id_address'=>2,
            'id_city'=>6,
            'id_country'=>7,
            'id_postcode'=>2,
            'id_product'=>34, 
            'quantity'=>2, 
            'id_color'=>4, 
            'id_size'=>2,
            'price'=>245.98, 
            'id_delivery'=>2, 
            'id_payment'=>1, 
            'received'=>1, 
            'received_date'=>1458471063,
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
    
    /**
     * Тестирует метод PurchasesModel::getProduct
     */
    public function testGetProduct()
    {
        $model = new PurchasesModel();
        $model->id_product = 1;
        
        $result = $model->product;
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getColor
     */
    public function testGetColor()
    {
        $model = new PurchasesModel();
        $model->id_color = 1;
        
        $result = $model->color;
        
        $this->assertInstanceOf(ColorsModel::class, $result);
    }
    
    /**
     * Тестирует метод PurchasesModel::getSize
     */
    public function testGetSize()
    {
        $model = new PurchasesModel();
        $model->id_size = 1;
        
        $result = $model->size;
        
        $this->assertInstanceOf(SizesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
