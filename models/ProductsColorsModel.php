<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные таблицы currency
 */
class ProductsColorsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД
    */
    const GET_FROM_DB = 'getFromBd';
    /**
     * Сценарий загрузки данных из формы добавления продукта
    */
    const GET_FROM_ADD_PRODUCT_FORM = 'getFromAddProductForm';
    
    public $id_products = '';
    public $id_colors = '';
    
    public function scenarios()
    {
        return [
            self::GET_FROM_DB=>['id_products', 'id_colors'],
            self::GET_FROM_ADD_PRODUCT_FORM=>['id_products', 'id_colors'],
        ];
    }
}