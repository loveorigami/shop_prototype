<?php

namespace app\filters;

use yii\base\{ActionFilter,
    ErrorException};
use app\exceptions\ExceptionsTrait;
use app\helpers\SessionHelper;

/**
 * Применяет фильтры к выборке ProductsModel
 */
class CartFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * Восстанавливает ранее сохраненное состояние корзины товаров 
     * @param object $action объект текущего действия
     * @return bool
     */
    public function beforeAction($action)
    {
        try {
            \Yii::$app->params['cartArray'] = SessionHelper::read(\Yii::$app->params['cartKey']) ?? [];
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            if (YII_ENV_DEV) {
                $this->throwException($t, __METHOD__);
            } else {
                $this->writeErrorInLogs($t, __METHOD__);
                return parent::beforeAction($action);
            }
        }
    }
}
