<?php

namespace app\models;

use yii\base\Model;

/**
 * Представляет данные таблицы subcategory
 */
class SubcategoryModel extends Model
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    
    public $id;
    public $name;
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'name'],
        ];
    }
}
