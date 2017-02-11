<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    BaseHandlerTrait,
    CartHandlerTrait};
use app\forms\PurchaseForm;
use app\savers\SessionArraySaver;
use app\helpers\HashHelper;
use app\finders\PurchasesSessionFinder;
use app\widgets\{CartWidget,
    ShortCartRedirectWidget};
use app\models\{CurrencyInterface,
    PurchasesModel};

/**
 * Обрабатывает запрос на обновление данных покупки
 */
class CartUpdateRequestHandler extends AbstractBaseHandler
{
    use BaseHandlerTrait, CartHandlerTrait;
    
    /**
     * Обрабатывает запрос на сохранение новой покупки в корзине
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new PurchaseForm(['scenario'=>PurchaseForm::UPDATE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $key = HashHelper::createCartKey();
                    $currentCurrencyModel = $this->getCurrentCurrency();
                    
                    $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                        'key'=>$key
                    ]);
                    $purchasesCollection = $finder->find();
                    
                    $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::UPDATE]);
                    $rawPurchasesModel->quantity = $form->quantity;
                    $rawPurchasesModel->id_color = $form->id_color;
                    $rawPurchasesModel->id_size = $form->id_size;
                    $rawPurchasesModel->id_product = $form->id_product;
                    if ($rawPurchasesModel->validate() === false) {
                        throw new ErrorException($this->modelError($rawPurchasesModel->errors));
                    }
                    
                    $purchasesCollection->update($rawPurchasesModel);
                    
                    $saver = new SessionArraySaver([
                        'key'=>$key,
                        'models'=>$purchasesCollection->asArray()
                    ]);
                    $saver->save();
                    
                    $dataArray = [];
                    
                    /*$service = \Yii::$app->registry->get(GetCartWidgetConfigService::class);
                    $cartWidgetConfig = $service->handle();
                    $dataArray['items'] = CartWidget::widget($cartWidgetConfig);*/
                    $cartWidgetConfig = $this->cartWidgetConfig($purchasesCollection, $currentCurrencyModel);
                    $dataArray['items'] = CartWidget::widget($cartWidgetConfig);
                    
                    /*$service = \Yii::$app->registry->get(GetShortCartWidgetConfigRedirectService::class);
                    $shortCartRedirectWidgetConfig = $service->handle();
                    $dataArray['shortCart'] = ShortCartRedirectWidget::widget($shortCartRedirectWidgetConfig);*/
                    $shortCartRedirectWidgetConfig = $this->shortCartRedirectWidgetConfig($purchasesCollection, $currentCurrencyModel);
                    $dataArray['shortCart'] = ShortCartRedirectWidget::widget($shortCartRedirectWidgetConfig);
                    
                    return $dataArray;
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
