<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminTodayOrdersWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture,
    UsersFixture};
use app\models\{CurrencyModel,
    UsersModel};

/**
 * Тестирует класс GetAdminTodayOrdersWidgetConfigService
 */
class GetAdminTodayOrdersWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'users'=>UsersFixture::class,
                'purchases'=>PurchasesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetAdminTodayOrdersWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminTodayOrdersWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('adminTodayOrdersWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAdminTodayOrdersWidgetConfigService::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $service = new GetAdminTodayOrdersWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
