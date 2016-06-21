<?php

namespace app\filters;

use yii\base\ActionFilter;

/**
 * Заполняет объект корзины данными сесии
 */
class ShoppingCartFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        $session = \Yii::$app->session;
        if ($session->has(\Yii::$app->params['cartKeyInSession'])) {
            $session->open();
            \Yii::$app->cart->setProductsArray($session->get(\Yii::$app->params['cartKeyInSession']));
            $session->close();
            \Yii::$app->cart->getShortData();
        }
        return parent::beforeAction($action);
    }
    
    public function afterAction($action, $result)
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set(\Yii::$app->params['cartKeyInSession'], \Yii::$app->cart->getProductsArray());
        $session->close();
        return parent::afterAction($action, $result);
    }
}
