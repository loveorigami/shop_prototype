<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CountryGetSaveCountryService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CountriesFixture;
use app\models\CountriesModel;

/**
 * Тестирует класс CountryGetSaveCountryService
 */
class CountryGetSaveCountryServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'countries'=>CountriesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CountryGetSaveCountryService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CountryGetSaveCountryService::class);
        
        $this->assertTrue($reflection->hasProperty('countriesModel'));
        $this->assertTrue($reflection->hasProperty('country'));
    }
    
    /**
     * Тестирует метод CountryGetSaveCountryService::getCity
     */
    public function testGetCity()
    {
        $service = new CountryGetSaveCountryService();
        
        $reflection = new \ReflectionProperty($service, 'country');
        $reflection->setAccessible(true);
        $reflection->setValue($service, self::$dbClass->countries['country_1']['country']);
        
        $reflection = new \ReflectionMethod($service, 'getCity');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInstanceOf(CountriesModel::class, $result);
    }
    
    /**
     * Тестирует метод CountryGetSaveCountryService::handle
     * если пуст CountryGetSaveCountryService::country
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: country
     */
    public function testHandleEmptyName()
    {
        $request = [];
        
        $service = new CountryGetSaveCountryService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод CountryGetSaveCountryService::handle
     * если country уже в СУБД
     */
    public function testHandleExistsAddress()
    {
        $request = ['country'=>self::$dbClass->countries['country_1']['country']];
        
        $service = new CountryGetSaveCountryService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(CountriesModel::class, $result);
    }
    
    /**
     * Тестирует метод CountryGetSaveCountryService::handle
     * если country еще не в СУБД
     */
    public function testHandleNotExistsAddress()
    {
        $request = ['country'=>'Австралия'];
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{countries}} WHERE [[country]]=:country')->bindValue(':country', 'Австралия')->queryOne();
        
        $this->assertEmpty($result);
        
        $service = new CountryGetSaveCountryService();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(CountriesModel::class, $result);
        
        $result = \Yii::$app->db->createCommand('SELECT * FROM {{countries}} WHERE [[country]]=:country')->bindValue(':country', 'Австралия')->queryOne();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('Австралия', $result['country']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
