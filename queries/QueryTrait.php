<?php

namespace app\queries;

use yii\base\ErrorException;
use yii\data\Pagination;
use app\models\ProductsModel;

/**
 * Коллекция методов для конструирования Query объектов 
 */
trait QueryTrait
{
    /**
     * Инкапсулирует общую для 
     * - ProductsListController::actionIndex
     * - ProductsListController::actionSearch
     * функциональность
     * @param array $extraWhere массив дополнительный условий, будет добавлен к WHERE
     * @return array
     */
    private function commonProducts(array $extraWhere=[])
    {
        try {
            $renderArray = array();
            
            $productsQuery = ProductsModel::find();
            $productsQuery->extendSelect(['id', 'date', 'name', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'active', 'seocode']);
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                $productsQuery->innerJoin('categories', '[[categories.id]]=[[products.id_category]]');
                $productsQuery->andWhere(['categories.seocode'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
            }
            
            if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                $productsQuery->innerJoin('subcategory', '[[subcategory.id]]=[[products.id_subcategory]]');
                $productsQuery->andWhere(['subcategory.seocode'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
            }
            
            $productsQuery->andWhere(['products.active'=>true]);
            
            if (!empty($extraWhere)) {
                $productsQuery->andWhere($extraWhere);
            }
            
            if (!$productsQuery->addFilters()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'addFilters()']));
            }
            
            if (!$productsQuery->extendLimit()) {
                throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'extendLimit()']));
            }
            
            $sortingField = \Yii::$app->filters->sortingField ? \Yii::$app->filters->sortingField : 'date';
            $sortingType = (\Yii::$app->filters->sortingType && \Yii::$app->filters->sortingType === 'ASC') ? SORT_ASC : SORT_DESC;
            $productsQuery->orderBy(['products.' . $sortingField=>$sortingType]);
            
            $renderArray['productsQuery'] = $productsQuery;
            
            $renderArray['paginator'] = $productsQuery->paginator;
            if (!$renderArray['paginator'] instanceof Pagination) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'Pagination']));
            }
            
            $renderArray['productsList'] = $productsQuery->all();
            if (!is_array($renderArray['productsList']) || !$renderArray['productsList'][0] instanceof ProductsModel) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'ProductsModel']));
            }
            
            return $renderArray;
        } catch (\Exception $e) {
            throw new ErrorException(\Yii::t('base/errors', "Method error {method}!\n", ['method'=>__METHOD__]) . $e->getMessage());
        }
    }
}
