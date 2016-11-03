<?php

namespace app\tests\widgets;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\widgets\CartWidget;
use app\models\{CurrencyModel,
    UsersModel};

class CartWidgetTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products'=>'app\tests\sources\fixtures\ProductsFixture',
                'currency'=>'app\tests\sources\fixtures\CurrencyFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        $fixture = self::$_dbClass->currency['currency_1'];
        
        $currencyQuery = CurrencyModel::find();
        $currencyQuery->extendSelect(['id', 'code', 'exchange_rate', 'main']);
        $currencyQuery->where(['[[currency.id]]'=>$fixture['id']]);
        $currencyModel = $currencyQuery->one();
        $currency = $currencyModel->toArray();
        \Yii::configure(\Yii::$app->currency, $currency);
    }
    
    /**
     * Тестирует метод CartWidget::widget()
     */
    public function testWidget()
    {
        $fixture = self::$_dbClass->currency['currency_1'];
        $fixtureProduct = self::$_dbClass->products['product_1'];
        
        \Yii::$app->params['cartArray'] = [
            ['id_product'=>1, 'quantity'=>2],
        ];
        
        $result = CartWidget::widget();
        
        $text = \Yii::t('base', 'Products in cart: {productsCount}, Total cost: {totalCost}', ['productsCount'=>\Yii::$app->params['cartArray'][0]['quantity'] , 'totalCost'=>number_format(($fixtureProduct['price'] * \Yii::$app->params['cartArray'][0]['quantity']), 2, ',', '')]);
        
        $this->assertRegExp('/^<div id="cart">/', $result);
        $this->assertRegExp('/<p>' . $text . ' ' . $fixture['code'] . '/', $result);
        $this->assertRegExp('/<a href=".+?">' . \Yii::t('base', 'To cart') . '<\/a>/', $result);
        $this->assertRegExp('/<form id="clean-cart-form" action=".+?" method="POST">/', $result);
    }
    
    /**
     * Тестирует метод CartWidget::widget() 
     * при условии, что массив \Yii::$app->params['cartArray'] пуст
     */
    public function testWidgetTwo()
    {
        $fixture = self::$_dbClass->currency['currency_1'];
        
        \Yii::$app->params['cartArray'] = [];
         
        $result = CartWidget::widget();
        
        $text = \Yii::t('base', 'Products in cart: {productsCount}, Total cost: {totalCost}', ['productsCount'=>0, 'totalCost'=>number_format(0, 2, ',', '')]);
        
        $expectedString = '<div id="cart"><p>' . $text . ' ' . $fixture['code'] . '</p><form id="clean-cart-form" action="../vendor/phpunit/phpunit/clean-cart" method="POST">' . PHP_EOL . '<input type="hidden" name="_csrf" value="' . \Yii::$app->request->csrfToken . '"><button type="submit" disabled>' . \Yii::t('base', 'Clean') . '</button></form></div>';
        
        $this->assertRegExp('/^<div id="cart">/', $result);
        $this->assertRegExp('/<p>' . $text . ' ' . $fixture['code'] . '/', $result);
        $this->assertRegExp('/<form id="clean-cart-form" action=".+?" method="POST">/', $result);
    }
    
    /**
     * Тестирует метод CartWidget::widget() 
     * при условии, что CartWidget::toCart = false
     */
    public function testWidgetThree()
    {
        $fixture = self::$_dbClass->currency['currency_1'];
        $fixtureProduct = self::$_dbClass->products['product_1'];
        
        \Yii::$app->params['cartArray'] = [
            ['id_product'=>1, 'quantity'=>2]
        ];
        
        $result = CartWidget::widget(['toCart'=>false]);
        
        $text = \Yii::t('base', 'Products in cart: {productsCount}, Total cost: {totalCost}', ['productsCount'=>\Yii::$app->params['cartArray'][0]['quantity'] , 'totalCost'=>number_format(($fixtureProduct['price'] * \Yii::$app->params['cartArray'][0]['quantity']), 2, ',', '')]);
        
        $this->assertRegExp('/^<div id="cart">/', $result);
        $this->assertRegExp('/<p>' . $text . ' ' . $fixture['code'] . '/', $result);
        $this->assertRegExp('/<form id="clean-cart-form" action=".+?" method="POST">/', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}