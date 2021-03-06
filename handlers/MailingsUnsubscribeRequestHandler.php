<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\services\GetCurrentCurrencyModelService;
use app\helpers\HashHelper;
use app\finders\{CategoriesFinder,
    CurrencyFinder,
    MailingsEmailFinder,
    PurchasesSessionFinder};
use app\forms\{AbstractBaseForm,
    ChangeCurrencyForm,
    UserLoginForm,
    UserMailingForm};
use app\validators\StripTagsValidator;

/**
 * Обрабатывает запрос на поиск данных для 
 * HTML формы удаления связи пользователя с рассылками
 */
class MailingsUnsubscribeRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $unsubscribeKey = $request->get(\Yii::$app->params['unsubscribeKey']) ?? null;
            $email = $request->get(\Yii::$app->params['emailKey']) ?? null;
            if (empty($unsubscribeKey) || empty($email)) {
                throw new NotFoundHttpException($this->error404());
            }
            
            $validator = new StripTagsValidator();
            $unsubscribeKey = $validator->validate($unsubscribeKey);
            $email = $validator->validate($email);
            
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if ($email === false) {
                throw new ErrorException($this->invalidError('email'));
            }
            
            $key = HashHelper::createHash([$email]);
            
            if ($unsubscribeKey !== $key) {
                throw new NotFoundHttpException($this->error404());
            }
            
            $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, [
                'email'=>$email
            ]);
            $mailingsModelArray = $finder->find();
            
            $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                'key'=>HashHelper::createCurrencyKey()
            ]);
            $currentCurrencyModel = $service->get();
            if (empty($currentCurrencyModel)) {
                throw new ErrorException($this->emptyError('currentCurrencyModel'));
            }
            
            $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                'key'=>HashHelper::createCartKey()
            ]);
            $ordersCollection = $finder->find();
            if (empty($ordersCollection)) {
                throw new ErrorException($this->emptyError('ordersCollection'));
            }
            
            $finder = \Yii::$app->registry->get(CurrencyFinder::class);
            $currencyArray = $finder->find();
            if (empty($currencyArray)) {
                throw new ErrorException($this->emptyError('currencyArray'));
            }
            
            $finder = \Yii::$app->registry->get(CategoriesFinder::class);
            $categoriesModelArray = $finder->find();
            if (empty($categoriesModelArray)) {
                throw new ErrorException($this->emptyError('categoriesModelArray'));
            }
            
            $changeCurrencyForm = new ChangeCurrencyForm([
                'scenario'=>ChangeCurrencyForm::SET,
                'id'=>$currentCurrencyModel->id,
                'url'=>Url::current()
            ]);
            
            $mailingForm = new UserMailingForm([
                'email'=>$email,
                'key'=>$unsubscribeKey
            ]);
            
            $userLoginForm = new UserLoginForm();
            
            if (empty($mailingsModelArray)) {
                $dataArray['unsubscribeEmptyWidgetConfig'] = $this->unsubscribeEmptyWidgetConfig($email);
            } else {
                $dataArray['unsubscribeFormWidgetConfig'] = $this->unsubscribeFormWidgetConfig($mailingForm, $mailingsModelArray);
            }
            
            $dataArray['userInfoWidgetConfig'] = $this->userInfoWidgetConfig(\Yii::$app->user, $userLoginForm);
            $dataArray['shortCartWidgetConfig'] = $this->shortCartWidgetConfig($ordersCollection, $currentCurrencyModel);
            $dataArray['currencyWidgetConfig'] = $this->currencyWidgetConfig($currencyArray, $changeCurrencyForm);
            $dataArray['searchWidgetConfig'] = $this->searchWidgetConfig();
            $dataArray['categoriesMenuWidgetConfig'] = $this->categoriesMenuWidgetConfig($categoriesModelArray);
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета UnsubscribeFormWidget
     * @param AbstractBaseForm $mailingForm
     * @param array $mailingsModelArray
     * @return array
     */
    private function unsubscribeFormWidgetConfig(AbstractBaseForm $mailingForm, array $mailingsModelArray): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = $mailingForm;
            $dataArray['mailings'] = $mailingsModelArray;
            $dataArray['template'] = 'unsubscribe-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
