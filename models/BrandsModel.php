<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы currency
 */
class BrandsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    
    public $id = '';
    public $brand = '';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id', 'brand'],
        ];
    }
}
