<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\base\ErrorException;
use app\helpers\SessionHelper;
use app\controllers\AbstractBaseController;

/**
 * Обрабатывает запросы данных, к которым необходимо применить фильтры
 */
class FilterController extends AbstractBaseController
{
    /**
     * Обрабатывает запрос на применение фильтров
     * @return redirect
     */
    public function actionAddFilters()
    {
        try {
            if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
                if (\Yii::$app->filters->validate()) {
                    $urlArray = $this->getRedirectUrl();
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
            return $this->redirect(Url::to($urlArray));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает запрос на очистку фильтров
     * @return redirect
     */
    public function actionCleanFilters()
    {
        try {
            if (empty(\Yii::$app->params['filtersKeyInSession'])) {
                throw new ErrorException('Не установлена переменная filtersKeyInSession!');
            }
            if (\Yii::$app->request->isPost && \Yii::$app->filters->load(\Yii::$app->request->post())) {
                if (\Yii::$app->filters->validate()) {
                    if (!SessionHelper::removeVarFromSession([\Yii::$app->params['filtersKeyInSession']])) {
                        throw new ErrorException('Ошибка при удалении переменной из сесии!');
                    }
                    if (!\Yii::$app->filters->clean()) {
                        throw new ErrorException('Ошибка при очистке фильтров!');
                    }
                    $urlArray = $this->getRedirectUrl();
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
            return $this->redirect(Url::to($urlArray));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Формирует URL для редиректа
     * @return string URI
     */
    private function getRedirectUrl()
    {
        try {
            if (empty(\Yii::$app->params['searchKey'])) {
                throw new ErrorException('Не установлена переменная searchKey!');
            }
            if (empty(\Yii::$app->params['categoryKey'])) {
                throw new ErrorException('Не установлена переменная categoryKey!');
            }
            if (empty(\Yii::$app->params['subCategoryKey'])) {
                throw new ErrorException('Не установлена переменная subCategoryKey!');
            }
            if (!empty(\Yii::$app->filters->search)) {
                $urlArray = ['products-list/search', \Yii::$app->params['searchKey']=>\Yii::$app->filters->search];
            } else {
                $urlArray = ['products-list/index'];
                if (!empty(\Yii::$app->filters->categories)) {
                    $urlArray = array_merge($urlArray, [\Yii::$app->params['categoryKey']=>\Yii::$app->filters->categories]);
                }
                if (!empty(\Yii::$app->filters->subcategory)) {
                    $urlArray = array_merge($urlArray, [\Yii::$app->params['subCategoryKey']=>\Yii::$app->filters->subcategory]);
                }
            }
            return $urlArray;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    public function behaviors()
    {
        return [
            ['class'=>'app\filters\ProductsListFilter'],
        ];
    }
}
