<?php

namespace app\tests\validators;

use PHPUnit\Framework\TestCase;
use yii\base\Model;
use app\validators\CreateProductCodeExistsValidator;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;

/**
 * Тестирует класс CreateProductCodeExistsValidator
 */
class CreateProductCodeExistsValidatorTests extends TestCase
{
    private static $_dbClass;
    private $validator;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ],
        ]);
        self::$_dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->validator = new CreateProductCodeExistsValidator();
    }
    
    /**
     * Тестирует метод CreateProductCodeExistsValidator::validateAttribute
     */
    public function testValidateAttribute()
    {
        $model = new class() extends Model {
            public $code;
        };
        $reflection = new \ReflectionProperty($model, 'code');
        $reflection->setValue($model, self::$_dbClass->products['product_1']['code']);
        
        $this->validator->validateAttribute($model, 'code');
        
        $this->assertNotEmpty($model->errors);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
