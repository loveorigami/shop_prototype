<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetMailingsFormWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\MailingsFixture;
use app\forms\MailingForm;

/**
 * Тестирует класс GetMailingsFormWidgetConfigService
 */
class GetMailingsFormWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'mailings'=>MailingsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства GetMailingsFormWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetMailingsFormWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('mailingsFormWidgetArray'));
    }
    
    /**
     * Тестирует метод GetMailingsFormWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetMailingsFormWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('mailings', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['mailings']);
        $this->assertInstanceOf(MailingForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
