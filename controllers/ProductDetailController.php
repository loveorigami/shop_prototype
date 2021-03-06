<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use app\actions\GetAction;
use app\handlers\ProductDetailIndexRequestHandler;
use app\filters\{ProductViewsCounterFilter,
    VisitorsCounterFilter};

/**
 * Обрабатывает запросы на получение информации о конкретном товаре
 */
class ProductDetailController extends Controller
{
    public function actions()
    {
        return [
            'index'=>[
                'class'=>GetAction::class,
                'handler'=>new ProductDetailIndexRequestHandler(),
                'view'=>'product-detail.twig',
            ],
        ];
    }
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::class,
                'rules'=>[
                    [
                        'allow'=>true,
                        'roles'=>['?', '@']
                    ],
                ],
            ],
            'counter'=>[
                'class'=>ProductViewsCounterFilter::class,
            ],
            'visitsCounter'=>[
                'class'=>VisitorsCounterFilter::class,
            ],
        ];
    }
}
