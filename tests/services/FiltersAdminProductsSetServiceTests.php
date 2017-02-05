<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\FiltersAdminProductsSetService;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersAdminProductsSetService
 */
class FiltersAdminProductsSetServiceTests extends TestCase
{
    /**
     * Тестирует метод FiltersAdminProductsSetService::handle
     * если не загрузилась форма
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: request
     */
    public function testHandleEmptyForm()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $filter = new FiltersAdminProductsSetService();
        $result = $filter->handle($request);
    }
    
    /**
     * Тестирует метод FiltersAdminProductsSetService::handle
     * если не валидны
     * @expectedException ErrorException
     * @expectedExceptionMessage Необходимо заполнить «Url».
     */
    public function testHandleErrorForm()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminProductsFiltersForm'=>[
                        'url'=>null
                    ],
                ];
            }
        };
        
        $filter = new FiltersAdminProductsSetService();
        $result = $filter->handle($request);
    }
    
    /**
     * Тестирует метод FiltersAdminProductsSetService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminProductsFiltersForm'=>[
                        'sortingField'=>'date',
                        'sortingType'=>SORT_ASC,
                        'colors'=>[1, 2],
                        'sizes'=>[1, 2],
                        'brands'=>[1, 2],
                        'category'=>1,
                        'subcategory'=>[1],
                        'active'=>true,
                        'url'=>'https://shop.com-4'
                    ]
                ];
            }
        };
        
        $filter = new FiltersAdminProductsSetService();
        $result = $filter->handle($request);
        
        $this->assertEquals('https://shop.com', $result);
        
        $key = HashHelper::createHash([\Yii::$app->params['adminProductsFilters']]);
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->get($key);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('sortingField', $result);
        $this->assertArrayHasKey('sortingType', $result);
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertArrayHasKey('category', $result);
        $this->assertArrayHasKey('subcategory', $result);
        $this->assertArrayHasKey('active', $result);
        
        $this->assertSame('date', $result['sortingField']);
        $this->assertSame(SORT_ASC, (int) $result['sortingType']);
        $this->assertSame([1, 2], $result['colors']);
        $this->assertSame([1, 2], $result['sizes']);
        $this->assertSame([1, 2], $result['brands']);
        $this->assertSame(1, $result['category']);
        $this->assertSame([1], $result['subcategory']);
        $this->assertSame(true, $result['active']);
        
        $session->remove($key);
        $session->close();
    }
}