<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    GetCartWidgetConfigService,
    GetCategoriesMenuWidgetConfigService,
    GetCurrencyWidgetConfigService,
    GetSearchWidgetConfigService,
    GetUserInfoWidgetConfigService,
    GetUserLoginWidgetConfigService};

/**
 * Формирует массив данных для рендеринга страницы с формой аутентификации
 */
class UserLoginFormService extends AbstractBaseService
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML формы аутентификации
     * @param array $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $dataArray = [];
                
                $service = \Yii::$app->registry->get(GetUserLoginWidgetConfigService::class);
                $dataArray['userLoginWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetUserInfoWidgetConfigService::class);
                $dataArray['userInfoWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetCartWidgetConfigService::class);
                $dataArray['cartWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetCurrencyWidgetConfigService::class);
                $dataArray['currencyWidgetConfig'] = $service->handle();
                
                $service = \Yii::$app->registry->get(GetSearchWidgetConfigService::class);
                $dataArray['searchWidgetConfig'] = $service->handle($request);
                
                $service = \Yii::$app->registry->get(GetCategoriesMenuWidgetConfigService::class);
                $dataArray['categoriesMenuWidgetConfig'] = $service->handle();
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
