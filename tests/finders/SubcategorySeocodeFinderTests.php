<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SubcategorySeocodeFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\SubcategoryFixture;
use app\models\SubcategoryModel;

/**
 * Тестирует класс SubcategorySeocodeFinder
 */
class SubcategorySeocodeFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'subcategory'=>SubcategoryFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства SubcategorySeocodeFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SubcategorySeocodeFinder::class);
        
        $this->assertTrue($reflection->hasProperty('seocode'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SubcategorySeocodeFinder::setSeocode
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSeocodeError()
    {
        $seocode = null;
        
        $widget = new SubcategorySeocodeFinder();
        $widget->setSeocode($seocode);
    }
    
    /**
     * Тестирует метод SubcategorySeocodeFinder::setSeocode
     */
    public function testSetSeocode()
    {
        $seocode = 'seocode';
        
        $widget = new SubcategorySeocodeFinder();
        $widget->setSeocode($seocode);
        
        $reflection = new \ReflectionProperty($widget, 'seocode');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод SubcategorySeocodeFinder::find
     * если пуст SubcategorySeocodeFinder::seocode
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: seocode
     */
    public function testFindEmptySeocode()
    {
        $finder = new SubcategorySeocodeFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод SubcategorySeocodeFinder::find
     */
    public function testFind()
    {
        $fixture = self::$dbClass->subcategory['subcategory_1'];
        
        $finder = new SubcategorySeocodeFinder();
        
        $reflection = new \ReflectionProperty($finder, 'seocode');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $fixture['seocode']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(SubcategoryModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
