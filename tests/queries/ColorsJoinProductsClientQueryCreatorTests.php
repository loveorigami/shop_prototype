<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ColorsJoinProductsClientQueryCreator;

/**
 * Тестирует класс app\queries\ColorsJoinProductsClientQueryCreator
 */
class ColorsJoinProductsClientQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        \Yii::$app->filters->clean();
        \Yii::$app->filters->cleanOther();
    }
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $_GET = [];
        
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new ColorsJoinProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[colors.id]],[[colors.color]] FROM {{colors}} JOIN {{products_colors}} ON [[colors.id]]=[[products_colors.id_colors]] JOIN {{products}} ON [[products_colors.id_products]]=[[products.id]] WHERE [[products.active]]=:active';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForCategory
     */
    public function testQueryForCategory()
    {
        $_GET = ['categories'=>'mensfootwear'];
        
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new ColorsJoinProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[colors.id]],[[colors.color]] FROM {{colors}} JOIN {{products_colors}} ON [[colors.id]]=[[products_colors.id_colors]] JOIN {{products}} ON [[products_colors.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] WHERE [[categories.seocode]]=:categories AND [[products.active]]=:active';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForSubCategory
     */
    public function testQueryForSubCategory()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new ColorsJoinProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[colors.id]],[[colors.color]] FROM {{colors}} JOIN {{products_colors}} ON [[colors.id]]=[[products_colors.id_colors]] JOIN {{products}} ON [[products_colors.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory AND [[products.active]]=:active';
        
        $this->assertEquals($query, $mockObject->query);
    }
}