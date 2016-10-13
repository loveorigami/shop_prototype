<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use yii\sphinx\{MatchExpression,
    Query};
use app\controllers\AbstractBaseController;
use app\helpers\InstancesHelper;
use app\queries\QueryTrait;
use app\models\{BrandsModel,
    ColorsModel,
    FiltersModel,
    ProductsModel,
    SizesModel};

/**
 * Обрабатывает запросы на получение списка продуктов
 */
class ProductsListController extends AbstractBaseController
{
    use QueryTrait;
    
    /**
     * Обрабатывает запрос к списку продуктов
     * @return string
     */
    public function actionIndex()
    {
        try {
            $productsQuery = $this->productsListQuery();
            
            $renderArray = array();
            $renderArray['paginator'] = $productsQuery->paginator;
            $renderArray['productsList'] = $productsQuery->all();
            
            if (!empty($renderArray['productsList'])) {
                $renderArray['filtersModel'] = new FiltersModel(['scenario'=>FiltersModel::GET_FROM_FORM]);
                
                $renderArray['colorsList'] = $this->colorsListQuery()->all();
                if (!is_array($renderArray['colorsList']) || !$renderArray['colorsList'][0] instanceof ColorsModel) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'ColorsModel']));
                }
                
                $renderArray['sizesList'] = $this->sizesListQuery()->all();
                if (!is_array($renderArray['sizesList']) || !$renderArray['sizesList'][0] instanceof SizesModel) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'SizesModel']));
                }
                
                $renderArray['brandsList'] = $this->brandsListQuery()->all();
                if (!is_array($renderArray['brandsList']) || !$renderArray['brandsList'][0] instanceof BrandsModel) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'BrandsModel']));
                }
            }
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/products-list/index'], 'label'=>\Yii::t('base', 'All catalog')];
            
            return $this->render('products-list.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает поисковый запрос к списку продуктов
     * @return string
     */
    public function actionSearch()
    {
        try {
            if (empty(\Yii::$app->request->get(\Yii::$app->params['searchKey']))) {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            $sphinxQuery = new Query();
            $sphinxQuery->select(['id']);
            $sphinxQuery->from('shop');
            $sphinxQuery->match(new MatchExpression(['*'=>\Yii::$app->request->get(\Yii::$app->params['searchKey'])]));
            $sphinxArray = $sphinxQuery->all();
            
            $productsQuery = $this->productsListQuery(['products.id'=>ArrayHelper::getColumn($sphinxArray, 'id')]);
            
            $renderArray = array();
            $renderArray['paginator'] = $productsQuery->paginator;
            $renderArray['productsList'] = $productsQuery->all();
            
            if (!empty($sphinxArray)) {
                $renderArray['filtersModel'] = new FiltersModel(['scenario'=>FiltersModel::GET_FROM_FORM]);
                
                $renderArray['colorsList'] = $this->colorsListQuerySearch(ArrayHelper::getColumn($sphinxArray, 'id'))->all();
                if (!is_array($renderArray['colorsList']) || !$renderArray['colorsList'][0] instanceof ColorsModel) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'ColorsModel']));
                }
                
                $renderArray['sizesList'] = $this->sizesListQuerySearch(ArrayHelper::getColumn($sphinxArray, 'id'))->all();
                if (!is_array($renderArray['sizesList']) || !$renderArray['sizesList'][0] instanceof SizesModel) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'SizesModel']));
                }
                
                $renderArray['brandsList'] = $this->brandsListQuerySearch(ArrayHelper::getColumn($sphinxArray, 'id'))->all();
                if (!is_array($renderArray['brandsList']) || !$renderArray['brandsList'][0] instanceof BrandsModel) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'BrandsModel']));
                }
            }
            
            \Yii::$app->params['breadcrumbs'] = ['label'=>\Yii::t('base', 'Searching results')];
            
            return $this->render('products-list.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
