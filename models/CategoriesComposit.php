<?php

namespace app\models;

use yii\base\ErrorException;
use app\models\{AbstractBaseComposit,
    CompositInterface,
    CategoriesModel};

/**
 * Реализует интерфейс доступа к данным о покупках в корзине
 */
class CategoriesComposit extends AbstractBaseComposit implements CompositInterface
{
    /**
     * Коллекция сущностей
     */
    protected $items = [];
    
    /**
     * Добавляет сущность в коллекцию
     * @param object $model CategoriesModel
     */
    public function add($model)
    {
        try {
            $this->items[] = $model;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает true, false в зависимости от того, пуст или нет CategoriesComposit::items
     */
    public function isEmpty()
    {
        try {
            return empty($this->items) ? true : false;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
