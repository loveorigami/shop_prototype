<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;
use app\models\{CategoriesModel,
    ColorsModel,
    FiltersModel};

/**
 * Коллекция методов для создания объектов,
 * вызываемых без изменений в разных блоках кода
 */
class InstancesHelper
{
    private static $_instancesArray = array();
    
    /**
     * Конструирует объекты для рендеринга
     * @return array
     */
    public static function getInstances(): array
    {
        try {
            # Массив объектов CategoriesModel для формирования меню категорий
            $categoriesQuery = CategoriesModel::find();
            $categoriesQuery->extendSelect(['id', 'name', 'seocode', 'active']);
            $categoriesQuery->orderBy(['categories.name'=>SORT_ASC]);
            $categoriesQuery->with('subcategory');
            self::$_instancesArray['categoriesList'] = $categoriesQuery->all();
            
            if (!is_array(self::$_instancesArray['categoriesList']) || !self::$_instancesArray['categoriesList'][0] instanceof CategoriesModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'CategoriesModel']));
            }
            
            # Объект FiltersModel для фильтрации вывода товаров
            self::$_instancesArray['filtersModel'] = new FiltersModel();
            
            # Массив объектов ColorsModel для фильтрации вывода товаров
            //self::$_instancesArray['colorsList'] = ColorsModel::find()->extendSelect(['id', 'color'])
            
            return self::$_instancesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
