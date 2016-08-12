<?php

namespace app\queries;

use app\queries\{AbstractFiltersQueryCreator,
    ProductsListQueryCreator};

/**
 * Конструирует запрос к БД для получения списка строк
 */
class BrandsJoinProductsQueryCreator extends AbstractFiltersQueryCreator
{
    /**
     * @var array массив для выборки данных
     */
    public $categoriesArrayFilters = [
        'tableOne'=>[ # Данные для выборки из таблицы products_brands
            'firstTableName'=>'brands',
            'firstTableFieldOn'=>'id',
            'secondTableName'=>'products_brands',
            'secondTableFieldOn'=>'id_brands',
        ],
        'tableTwo'=>[ # Данные для выборки из таблицы products
            'firstTableName'=>'products_brands',
            'firstTableFieldOn'=>'id_products',
            'secondTableName'=>'products',
            'secondTableFieldOn'=>'id',
        ],
    ];
    
    public function init()
    {
        try {
            parent::init();
            
            $reflectionParent = new \ReflectionClass('app\queries\ProductsListQueryCreator');
            if ($reflectionParent->hasProperty('categoriesArrayFilters')) {
                $parentCategoriesArrayFilters = $reflectionParent->getProperty('categoriesArrayFilters')->getValue(new ProductsListQueryCreator);
            }
            $this->categoriesArrayFilters = array_merge($parentCategoriesArrayFilters, $this->categoriesArrayFilters);
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
