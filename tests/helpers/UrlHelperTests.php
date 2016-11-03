<?php

namespace app\tests\helpers;

use yii\helpers\Url;
use PHPUnit\Framework\TestCase;
use app\helpers\UrlHelper;
use app\helpers\SessionHelper;
use app\controllers\CartController;

/**
 * Тестирует класс app\helpers\UrlHelper
 */
class UrlHelperTests extends TestCase
{
    /**
     * Тестирует метод UrlHelper::previous
     */
    public function testPrevious()
    {
        $result = UrlHelper::previous('test');
        $this->assertEquals('../vendor/phpunit/phpunit/catalog', $result);
        
        Url::remember('https://shop.com/cart', 'test');
        $result = UrlHelper::previous('test');
        $this->assertEquals('https://shop.com/cart', $result);
        
        \Yii::$app->controller = new CartController('cart', \Yii::$app);
        $this->assertEquals('../vendor/phpunit/phpunit/cart', UrlHelper::current());
        $result = UrlHelper::previous('test');
        $this->assertEquals('https://shop.com/cart', $result);
    }
    
    public static function tearDownAfterClass()
    {
        SessionHelper::remove(['test']);
    }
}