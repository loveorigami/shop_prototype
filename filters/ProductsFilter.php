<?php

namespace app\filters;

use yii\base\ActionFilter;
use yii\helpers\Url;
use app\exceptions\ExceptionsTrait;
use app\helpers\{SessionHelper,
    StringHelper};

/**
 * Применяет фильтры к выборке ProductsModel
 */
class ProductsFilter extends ActionFilter
{
    use ExceptionsTrait;
    
    /**
     * Восстанавливает ранее сохраненное состояние свойств FiltersModel 
     * для текущего URL
     * @param object $action объект текущего действия
     * @return bool
     */
    public function beforeAction($action)
    {
        try {
            $key = StringHelper::cutPage(Url::current());
            $data = SessionHelper::read($key);
            if (!empty($data)) {
                \Yii::configure(\Yii::$app->filters, $data);
            }
            
            return parent::beforeAction($action);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}