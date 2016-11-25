<?php

namespace app\actions;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use yii\web\NotFoundHttpException;
use app\actions\AbstractBaseAction;
use app\services\SearchServiceInterface;
use app\exceptions\ExceptionsTrait;

/**
 * Обрабатывает запрос на вывод товаров из СУБД
 */
class SearchAction extends AbstractBaseAction
{
    /**
     * @var object SearchServiceInterface для поиска данных по запросу
     */
    private $service;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    /**
     * @var array массив дополнительных данных, которые будут доступны в шаблоне
     */
    public $additions = [];
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->service)) {
                throw new ErrorException(ExceptionsTrait::emptyError('service'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('service'));
            }
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function run()
    {
        try {
            $collection = $this->service->search(\Yii::$app->request->get());
            
            if ($collection->isEmpty()) {
                throw new NotFoundHttpException(ExceptionsTrait::emptyError('collection'));
            }
            
            Url::remember(Url::current(), \Yii::$app->id);
            
            return $this->controller->render($this->view, ArrayHelper::merge($this->renderArray, ['collection'=>$collection]));
        } catch (NotFoundHttpException $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            throw $e;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает SearchServiceInterface свойству SearchAction::service
     * @param object $service SearchServiceInterface
     */
    public function setService(SearchServiceInterface $service)
    {
        try {
            $this->service = $service;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}