<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\CountryCountryFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\CountriesFixture;
use app\models\CountriesModel;

/**
 * Тестирует класс CountryCountryFinder
 */
class CountryCountryFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'countries'=>CountriesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства CountryCountryFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CountryCountryFinder::class);
        
        $this->assertTrue($reflection->hasProperty('country'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод CountryCountryFinder::setCountry
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCountryError()
    {
        $country = null;
        
        $widget = new CountryCountryFinder();
        $widget->setCountry($country);
    }
    
    /**
     * Тестирует метод CountryCountryFinder::setCountry
     */
    public function testSetCountry()
    {
        $country = 'country';
        
        $widget = new CountryCountryFinder();
        $widget->setCountry($country);
        
        $reflection = new \ReflectionProperty($widget, 'country');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод CountryCountryFinder::find
     * если пуст CountryCountryFinder::country
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: country
     */
    public function testFindEmptySeocode()
    {
        $finder = new CountryCountryFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод CountryCountryFinder::find
     */
    public function testFind()
    {
        $finder = new CountryCountryFinder();
        
        $reflection = new \ReflectionProperty($finder, 'country');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, self::$dbClass->countries['country_1']['country']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(CountriesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
