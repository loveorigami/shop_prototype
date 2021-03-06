<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\handlers\AbstractBaseHandler;
use app\services\{AddressGetSaveAddressService,
    CityGetSaveCityService,
    CountryGetSaveCountryService,
    EmailGetSaveEmailService,
    GetCurrentCurrencyModelService,
    NameGetSaveNameService,
    PhoneGetSavePhoneService,
    PostcodeGetSavePostcodeService,
    ReceivedOrderEmailService,
    RegistrationEmailService,
    SurnameGetSaveSurnameService};
use app\forms\CustomerInfoForm;
use app\models\{PurchasesModel,
    UsersModel};
use app\finders\{PurchasesSessionFinder,
    UserEmailFinder};
use app\helpers\HashHelper;
use app\savers\{ModelSaver,
    PurchasesArraySaver};
use app\removers\SessionRemover;

/**
 * Обрабатывает запрос, сохраняющий оформленные покупки в БД
 */
class CartCheckoutAjaxRequestHandler extends AbstractBaseHandler
{
    /**
     * Обрабатывает запрос на сохранение данных о покупках
     * @param array $request
     */
    public function handle($request)
    {
        try {
            $form = new CustomerInfoForm(['scenario'=>CustomerInfoForm::CHECKOUT]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $service = \Yii::$app->registry->get(NameGetSaveNameService::class, [
                            'name'=>$form->name
                        ]);
                        $namesModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(SurnameGetSaveSurnameService::class, [
                            'surname'=>$form->surname
                        ]);
                        $surnamesModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(EmailGetSaveEmailService::class, [
                            'email'=>$form->email
                        ]);
                        $emailsModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(PhoneGetSavePhoneService::class, [
                            'phone'=>$form->phone
                        ]);
                        $phonesModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(AddressGetSaveAddressService::class, [
                            'address'=>$form->address
                        ]);
                        $addressModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(CityGetSaveCityService::class, [
                            'city'=>$form->city
                        ]);
                        $citiesModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(CountryGetSaveCountryService::class, [
                            'country'=>$form->country
                        ]);
                        $countriesModel = $service->get();
                        
                        $service = \Yii::$app->registry->get(PostcodeGetSavePostcodeService::class, [
                            'postcode'=>$form->postcode
                        ]);
                        $postcodesModel = $service->get();
                        
                        if ((bool) $form->create === true && \Yii::$app->user->isGuest === true) {
                            $rawUsersModel = new UsersModel(['scenario'=>UsersModel::SAVE]);
                            $rawUsersModel->id_email = $emailsModel->id;
                            $rawUsersModel->password = password_hash($form->password, PASSWORD_DEFAULT);
                            $rawUsersModel->id_name = $namesModel->id;
                            $rawUsersModel->id_surname = $surnamesModel->id;
                            $rawUsersModel->id_phone = $phonesModel->id;
                            $rawUsersModel->id_address = $addressModel->id;
                            $rawUsersModel->id_city = $citiesModel->id;
                            $rawUsersModel->id_country = $countriesModel->id;
                            $rawUsersModel->id_postcode = $postcodesModel->id;
                            if ($rawUsersModel->validate() === false) {
                                throw new ErrorException($this->modelError($rawUsersModel->errors));
                            }
                            
                            $saver = new ModelSaver([
                                'model'=>$rawUsersModel
                            ]);
                            $saver->save();
                            
                            $mailService = new RegistrationEmailService([
                                'email'=>$form->email
                            ]);
                            $mailService->get();
                            
                            $finder = \Yii::$app->registry->get(UserEmailFinder::class, [
                                'email'=>$form->email
                            ]);
                            $newUsersModel = $finder->find();
                        }
                        
                        if ((bool) $form->change === true && \Yii::$app->user->isGuest === false) {
                            $usersModel = \Yii::$app->user->identity;
                            $usersModel->scenario = UsersModel::UPDATE;
                            $usersModel->id_name = $namesModel->id;
                            $usersModel->id_surname = $surnamesModel->id;
                            $usersModel->id_phone = $phonesModel->id;
                            $usersModel->id_address = $addressModel->id;
                            $usersModel->id_city = $citiesModel->id;
                            $usersModel->id_country = $countriesModel->id;
                            $usersModel->id_postcode = $postcodesModel->id;
                            if ($usersModel->validate() === false) {
                                throw new ErrorException($this->modelError($usersModel->errors));
                            }
                            
                            $saver = new ModelSaver([
                                'model'=>$usersModel
                            ]);
                            $saver->save();
                        }
                        
                        $finder = \Yii::$app->registry->get(PurchasesSessionFinder::class, [
                            'key'=>HashHelper::createCartKey()
                        ]);
                        $ordersCollection = $finder->find();
                        if (empty($ordersCollection)) {
                            throw new ErrorException($this->emptyError('ordersCollection'));
                        }
                        
                        $rawPurchasesModel = new PurchasesModel(['scenario'=>PurchasesModel::SAVE]);
                        
                        $rawPurchasesModelArray = [];
                        
                        foreach ($ordersCollection as $order) {
                            $cloneRawPurchasesModel = clone $rawPurchasesModel;
                            $cloneRawPurchasesModel->id_user = \Yii::$app->user->id ?? $newUsersModel->id ?? 0;
                            $cloneRawPurchasesModel->id_name = $namesModel->id;
                            $cloneRawPurchasesModel->id_surname = $surnamesModel->id;
                            $cloneRawPurchasesModel->id_email = $emailsModel->id;
                            $cloneRawPurchasesModel->id_phone = $phonesModel->id;
                            $cloneRawPurchasesModel->id_address = $addressModel->id;
                            $cloneRawPurchasesModel->id_city = $citiesModel->id;
                            $cloneRawPurchasesModel->id_country = $countriesModel->id;
                            $cloneRawPurchasesModel->id_postcode = $postcodesModel->id;
                            $cloneRawPurchasesModel->id_product = $order->id_product;
                            $cloneRawPurchasesModel->quantity = $order->quantity;
                            $cloneRawPurchasesModel->id_color = $order->id_color;
                            $cloneRawPurchasesModel->id_size = $order->id_size;
                            $cloneRawPurchasesModel->price = $order->price;
                            $cloneRawPurchasesModel->id_delivery = $form->id_delivery;
                            $cloneRawPurchasesModel->id_payment = $form->id_payment;
                            $cloneRawPurchasesModel->received = true;
                            $cloneRawPurchasesModel->received_date = time();
                            if ($cloneRawPurchasesModel->validate() === false) {
                                throw new ErrorException($this->modelError($cloneRawPurchasesModel->errors));
                            }
                            $rawPurchasesModelArray[] = $cloneRawPurchasesModel;
                        }
                        
                        $saver = new PurchasesArraySaver([
                            'models'=>$rawPurchasesModelArray
                        ]);
                        $saver->save();
                        
                        $service = \Yii::$app->registry->get(GetCurrentCurrencyModelService::class, [
                            'key'=>HashHelper::createCurrencyKey()
                        ]);
                        $currentCurrencyModel = $service->get();
                        if (empty($currentCurrencyModel)) {
                            throw new ErrorException($this->emptyError('currentCurrencyModel'));
                        }
                        
                        $mailService = new ReceivedOrderEmailService([
                            'email'=>$form->email,
                            'ordersCollection'=>$ordersCollection,
                            'customerInfoForm'=>$form,
                            'currentCurrencyModel'=>$currentCurrencyModel
                        ]);
                        $mailService->get();
                        
                        $remover = new SessionRemover([
                            'keys'=>[HashHelper::createCartKey(), HashHelper::createCartCustomerKey()],
                        ]);
                        $remover->remove();
                        
                        $transaction->commit();
                        
                        return Url::to(['/products-list/index']);
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
