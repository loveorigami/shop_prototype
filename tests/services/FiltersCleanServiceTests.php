<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\FiltersCleanService;
use app\helpers\HashHelper;
use yii\helpers\Url;
use app\controllers\ProductsListController;
use yii\web\Request;

/**
 * Тестирует класс FiltersCleanService
 */
class FiltersCleanServiceTests extends TestCase
{
    /**
     * Тестирует метод FiltersCleanService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $url = Url::current();
        
        $key = HashHelper::createFiltersKey($url);
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, ['a'=>1, 'b'=>'cdef', 'c'=>23]);
        
        $result = $session->get($key);
        
        $this->assertSame(['a'=>1, 'b'=>'cdef', 'c'=>23], $result);
        
        $request = new class() extends Request {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'FiltersForm'=>[
                        'url'=>Url::current()
                    ],
                ];
            }
        };
        
        $filter = new FiltersCleanService();
        $result = $filter->handle($request);
        
        $this->assertSame($url, $result);
        
        $result = $session->has($key);
        
        $this->assertFalse($result);
        
        $session->close();
    }
}
