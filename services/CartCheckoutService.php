<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCartBackToCartLinkWidgetConfigService,
    GetShortCartWidgetConfigRedirectService,
    GetCartCheckoutLinkWidgetConfigService,
    GetCartCheckoutWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrencyWidgetConfigService,
    GetSearchWidgetConfigService,
    GetUserInfoWidgetConfigService};

/**
 * Формирует массив данных для рендеринга страницы оформления заказа
 */
class CartCheckoutService extends AbstractBaseService
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы оформления заказа
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetCartCheckoutWidgetConfigService::class);
                $dataArray['cartCheckoutWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetUserInfoWidgetConfigService::class);
                $dataArray['userInfoWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetShortCartWidgetConfigRedirectService::class);
                $dataArray['shortCartRedirectWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetCurrencyWidgetConfigService::class);
                $dataArray['currencyWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetSearchWidgetConfigService::class);
                $dataArray['searchWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetCategoriesMenuWidgetConfigService::class);
                $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetCartBackToCartLinkWidgetConfigService::class);
                $dataArray['cartBackToCartLinkWidgetConfig'] = $service->handle();
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}