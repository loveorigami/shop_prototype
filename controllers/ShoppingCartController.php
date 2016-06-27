<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\models\ProductsModel;
use app\models\UsersModel;
use app\models\EmailsModel;
use app\models\AddressModel;
use app\models\PhonesModel;
use app\models\DeliveriesModel;
use app\models\PaymentsModel;
use app\models\UsersPurchasesModel;
use app\mappers\UsersInsertMapper;
use app\mappers\EmailsByEmailMapper;
use app\mappers\EmailsInsertMapper;
use app\mappers\AddressByAddressMapper;
use app\mappers\AddressInsertMapper;
use app\mappers\PhonesByPhoneMapper;
use app\mappers\PhonesInsertMapper;
use app\mappers\DeliveriesByIdMapper;
use app\mappers\PaymentsMapper;
use app\mappers\PaymentsByIdMapper;
use app\mappers\UsersPurchasesInsertMapper;

/**
 * Управляет процессом добавления комментария
 */
class ShoppingCartController extends AbstractBaseController
{
    /**
     * Управляет процессом добавления товара в корзину
     * @return redirect
     */
    public function actionAddToCart()
    {
        try {
            $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    if (!\Yii::$app->cart->addProduct($model)) {
                        throw new ErrorException('Ошибка при добавлении товара в корзину!');
                    }
                    return $this->redirect(Url::to(['product-detail/index', 'categories'=>$model->categories, 'subcategory'=>$model->subcategory, 'id'=>$model->id]));
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом очистки корзины
     * @return redirect
     */
    public function actionClearCart()
    {
        try {
            $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_CLEAR_CART]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    if (!\Yii::$app->cart->clearProductsArray()) {
                        throw new ErrorException('Ошибка при очистке корзины!');
                    }
                    if (!empty($model->id)) {
                        return $this->redirect(Url::to(['product-detail/index', 'categories'=>$model->categories, 'subcategory'=>$model->subcategory, 'id'=>$model->id]));
                    } else {
                        $urlArray = ['products-list/index'];
                        if (!empty($model->categories)) {
                            $urlArray = array_merge($urlArray, ['categories'=>$model->categories]);
                        }
                        if (!empty($model->subcategory)) {
                            $urlArray = array_merge($urlArray, ['subcategory'=>$model->subcategory]);
                        }
                        return $this->redirect(Url::to($urlArray));
                    }
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом вывода полной информации о покупках на странице корзины
     * @return string
     */
    public function actionIndex()
    {
        try {
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при формировании массива данных!');
            }
            return $this->render('shopping-cart.twig', $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом удаления из корзины определенного продукта
     * @return string
     */
    public function actionRemoveProduct()
    {
        try {
            $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_FOR_REMOVE]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    if (!\Yii::$app->cart->removeProduct($model)) {
                        throw new ErrorException('Ошибка при удалении товара из корзины!');
                    }
                    if (!empty(\Yii::$app->cart->getProductsArray())) {
                        return $this->redirect(Url::to(['shopping-cart/index']));
                    } else {
                        return $this->redirect(Url::to(['products-list/index']));
                    }
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом обновления данных определенного продукта
     * @return string
     */
    public function actionUpdateProduct()
    {
        try {
            $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM_TO_CART]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    if (!\Yii::$app->cart->updateProduct($model)) {
                        throw new ErrorException('Ошибка при обновлении данных о товаре в корзине!');
                    }
                    return $this->redirect(Url::to(['shopping-cart/index']));
                }
            } else {
                return $this->redirect(Url::to(['products-list/index']));
            }
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом добавления адреса доставки, контактных данных
     * @return string
     */
    public function actionAddressContacts()
    {
        try {
            if (empty(\Yii::$app->cart->getProductsArray())) {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            if (!empty(\Yii::$app->cart->user)) {
                $usersModel = \Yii::$app->cart->user;
            } else {
                $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_CART_FORM]);
            }
            if (!empty(\Yii::$app->cart->user->emails)) {
                $emailsModel = \Yii::$app->cart->user->emails;
            } else {
                $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
            }
            if (!empty(\Yii::$app->cart->user->address)) {
                $addressModel = \Yii::$app->cart->user->address;
            } else {
                $addressModel = new AddressModel(['scenario'=>AddressModel::GET_FROM_FORM]);
            }
            if (!empty(\Yii::$app->cart->user->phones)) {
                $phonesModel = \Yii::$app->cart->user->phones;
            } else {
                $phonesModel = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_FORM]);
            }
            if (!empty(\Yii::$app->cart->user->deliveries)) {
                $deliveriesModel = \Yii::$app->cart->user->deliveries;
            } else {
                $deliveriesModel = new DeliveriesModel(['scenario'=>DeliveriesModel::GET_FROM_FORM]);
            }
            if (!empty(\Yii::$app->cart->user->payments)) {
                $paymentsModel = \Yii::$app->cart->user->payments;
            } else {
                $paymentsModel = new PaymentsModel(['scenario'=>PaymentsModel::GET_FROM_FORM]);
            }
            
            if (\Yii::$app->request->isPost && $usersModel->load(\Yii::$app->request->post()) && $emailsModel->load(\Yii::$app->request->post()) && $addressModel->load(\Yii::$app->request->post()) && $phonesModel->load(\Yii::$app->request->post()) && $deliveriesModel->load(\Yii::$app->request->post()) && $paymentsModel->load(\Yii::$app->request->post())) {
                if ($usersModel->validate()) {
                    if (empty(\Yii::$app->cart->user)) {
                        \Yii::$app->cart->user = $usersModel;
                    }
                }
                if ($emailsModel->validate()) {
                    if (empty(\Yii::$app->cart->user->emails)) {
                        \Yii::$app->cart->user->emails = $emailsModel;
                    }
                }
                if ($addressModel->validate()) {
                    if (empty(\Yii::$app->cart->user->address)) {
                        \Yii::$app->cart->user->address = $addressModel;
                    }
                }
                if ($phonesModel->validate()) {
                    if (empty(\Yii::$app->cart->user->phones)) {
                        \Yii::$app->cart->user->phones = $phonesModel;
                    }
                }
                if ($deliveriesModel->validate()) {
                    if (empty(\Yii::$app->cart->user->deliveries)) {
                        \Yii::$app->cart->user->deliveries = $deliveriesModel;
                    }
                }
                if ($paymentsModel->validate()) {
                    if (empty(\Yii::$app->cart->user->payments)) {
                        \Yii::$app->cart->user->payments = $paymentsModel;
                    }
                }
                return $this->redirect(Url::to(['shopping-cart/check-pay']));
            }
            
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при получении данных для рендеринга!');
            }
            $dataForRender = array_merge($dataForRender, ['usersModel'=>$usersModel, 'emailsModel'=>$emailsModel, 'addressModel'=>$addressModel, 'phonesModel'=>$phonesModel, 'deliveriesModel'=>$deliveriesModel, 'paymentsModel'=>$paymentsModel]);
            return $this->render('address-contacts.twig', $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом подтверждения заказа
     * @return string
     */
    public function actionCheckPay()
    {
        try {
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при получении данных для рендеринга!');
            }
            return $this->render('check-pay.twig', $dataForRender);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом загрузки данных в БД после подтверждения покупки,
     * отправки сообщений покупателю и менеджеру магазина,
     * оплаты в случае, если выбрана онлайн-оплата
     * @return string
     */
    public function actionPay()
    {
        try {
            if (empty(\Yii::$app->cart->getProductsArray())) {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            if (empty(\Yii::$app->cart->user) || !is_object(\Yii::$app->cart->user)) {
                throw new ErrorException('Недоступны данные для сохранения сведений о покупке!');
            }
            if (!empty(\Yii::$app->cart->user->emails) && is_object(\Yii::$app->cart->user->emails)) {
                $emailsModel = $this->getEmailsModel(\Yii::$app->cart->user->emails);
                \Yii::$app->cart->user->id_emails = $emailsModel->id;
            } else {
                throw new ErrorException('Недоступны данные для сохранения сведений о покупке!');
            }
            if (!empty(\Yii::$app->cart->user->address) && is_object(\Yii::$app->cart->user->address)) {
                $addressModel = $this->getAddressModel(\Yii::$app->cart->user->address);
                \Yii::$app->cart->user->id_address = $addressModel->id;
            } else {
                throw new ErrorException('Недоступны данные для сохранения сведений о покупке!');
            }
            if (!empty(\Yii::$app->cart->user->phones) && is_object(\Yii::$app->cart->user->phones)) {
                $phonesModel = $this->getPhonesModel(\Yii::$app->cart->user->phones);
                \Yii::$app->cart->user->id_phones = $phonesModel->id;
            } else {
                throw new ErrorException('Недоступны данные для сохранения сведений о покупке!');
            }
            if ($this->setUsersModel(\Yii::$app->cart->user)) {
                if ($this->setUsersPurchasesModel(\Yii::$app->cart->user->id, \Yii::$app->cart->getProductsArray(), \Yii::$app->cart->user->deliveries->id, \Yii::$app->cart->user->payments->id)) {
                    if (!\Yii::$app->cart->clearProductsArray()) {
                        throw new ErrorException('Ошибка при очистке корзины!');
                    }
                } else {
                    throw new ErrorException('Ошибка при сохранении связи пользователя с покупкой в процессе оформления заказа!');
                }
            } else {
                throw new ErrorException('Ошибка при сохранении данных пользователя в процессе оформления покупки!');
            }
            return 'SAVED!';
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет или создает UsersModel
     * Проверяет, авторизирован ли user в системе, если да, обновляет данные,
     * если нет, создает новую запись в БД
     * @param object $usersModel экземпляр UsersModel
     * @return object
     */
    private function setUsersModel(UsersModel $usersModel)
    {
        try {
            $usersInsertMapper = new UsersInsertMapper([
                'tableName'=>'users',
                'fields'=>['login', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
                'objectsArray'=>[$usersModel],
            ]);
            $result = $usersInsertMapper->setGroup();
            if (!$result) {
                throw new ErrorException('Не удалось обновить данные в БД!');
            }
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Получает EmailsModel для переданного в форму email
     * Проверяет, существет ли запись в БД для такого email, если да, возвращает ее,
     * если нет, создает новую запись в БД
     * @param object $emailsModel экземпляр EmailsModel
     * @return object
     */
    private function getEmailsModel(EmailsModel $emailsModel)
    {
        try {
            $emailsByEmailMapper = new EmailsByEmailMapper([
                'tableName'=>'emails',
                'fields'=>['id', 'email'],
                'model'=>$emailsModel
            ]);
            $result = $emailsByEmailMapper->getOneFromGroup();
            if (is_object($result) && $result instanceof EmailsModel) {
                $emailsModel = $result;
            } else {
                $emailsInsertMapper = new EmailsInsertMapper([
                    'tableName'=>'emails',
                    'fields'=>['email'],
                    'objectsArray'=>[$emailsModel],
                ]);
                if (!$emailsInsertMapper->setGroup()) {
                    throw new ErrorException('Не удалось обновить данные в БД!');
                }
            }
            return $emailsModel;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
     /**
      * Получает AddressModel для переданного в форму address
     * Проверяет, существет ли запись в БД для address, если да, прекращает выполнение,
     * если нет, создает новую запись в БД
     * @param object $addressModel экземпляр AddressModel
     * @return object
     */
     private function getAddressModel(AddressModel $addressModel)
     {
        try {
            $addressByAddressMapper = new AddressByAddressMapper([
                'tableName'=>'address',
                'fields'=>['id', 'address', 'city', 'country', 'postcode'],
                'model'=>$addressModel
            ]);
            $result = $addressByAddressMapper->getOneFromGroup();
            if (is_object($result) && $result instanceof AddressModel) {
                $addressModel = $result;
            } else {
                $addressInsertMapper = new AddressInsertMapper([
                    'tableName'=>'address',
                    'fields'=>['address', 'city', 'country', 'postcode'],
                    'objectsArray'=>[$addressModel],
                ]);
                if (!$addressInsertMapper->setGroup()) {
                    throw new ErrorException('Не удалось обновить данные в БД!');
                }
            }
            return $addressModel;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
     }
     
    /**
      * Получает PhonesModel для переданного в форму phone
     * Проверяет, существет ли запись в БД для phones, если да, прекращает выполнение,
     * если нет, создает новую запись в БД
     * @param object $phonesModel экземпляр PhonesModel
     * @return object
     */
    private function getPhonesModel(PhonesModel $phonesModel)
    {
        try {
            $phonesByPhoneMapper = new PhonesByPhoneMapper([
                'tableName'=>'phones',
                'fields'=>['id', 'phone'],
                'model'=>$phonesModel
            ]);
            $result = $phonesByPhoneMapper->getOneFromGroup();
            if (is_object($result) && $result instanceof PhonesModel) {
                $phonesModel = $result;
            } else {
                $phonesInsertMapper = new PhonesInsertMapper([
                    'tableName'=>'phones',
                    'fields'=>['phone'],
                    'objectsArray'=>[$phonesModel],
                ]);
                if (!$phonesInsertMapper->setGroup()) {
                    throw new ErrorException('Не удалось обновить данные в БД!');
                }
            }
            return $phonesModel;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
      * Получает DeliveriesModel для переданного в форму id
     * @param object $deliveriesModel экземпляр DeliveriesModel
     * @return object
     */
    private function getDeliveriesModel(DeliveriesModel $deliveriesModel)
    {
        try {
            $deliveriesByIdMapper = new DeliveriesByIdMapper([
                'tableName'=>'deliveries',
                'fields'=>['id', 'name', 'description', 'price'],
                'model'=>$deliveriesModel,
            ]);
            $result = $deliveriesByIdMapper->getOneFromGroup();
            if (!is_object($result) || !$result instanceof DeliveriesModel) {
                throw new ErrorException('Ошибка при получении данных из БД!');
            }
            $deliveriesModel = $result;
            return $deliveriesModel;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
      * Получает PaymentsModel для переданного в форму id
     * @param object $paymentsModel экземпляр PaymentsModel
     * @return object
     */
    private function getPaymentsModel(PaymentsModel $paymentsModel)
    {
        try {
            $paymentsByIdMapper = new PaymentsByIdMapper([
                'tableName'=>'payments',
                'fields'=>['id', 'name', 'description'],
                'model'=>$paymentsModel,
            ]);
            $result = $paymentsByIdMapper->getOneFromGroup();
            if (!is_object($result) || !$result instanceof PaymentsModel) {
                throw new ErrorException('Ошибка при получении данных из БД!');
            }
            $paymentsModel = $result;
            return $paymentsModel;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает UsersPurchasesModel
     * создает новую запись в БД, вязывающую пользователя с купленным товаром
     * @param object $usersPurchasesModel экземпляр UsersPurchasesModel
     * @return object
     */
    private function setUsersPurchasesModel($id_users, Array $productsArray, $id_deliveries, $id_payments)
    {
        try {
            if (!is_numeric($id_users) || !is_numeric($id_deliveries) || !is_numeric($id_payments)) {
                throw new ErrorException('Ошибка в типе переданных аргументов!');
            }
            if (!is_array($productsArray) || empty($productsArray)) {
                throw new ErrorException('Ошибка в типе переданных аргументов!');
            }
            $arrayToDb = [];
            foreach ($productsArray as $product) {
                $arrayToDb[] = ['id_users'=>$id_users, 'id_products'=>$product->id, 'id_deliveries'=>$id_deliveries, 'id_payments'=>$id_payments];
            }
            $usersPurchasesInsertMapper = new UsersPurchasesInsertMapper([
                'tableName'=>'users_purchases',
                'fields'=>['id_users', 'id_products', 'id_deliveries', 'id_payments'],
                'DbArray'=>$arrayToDb,
            ]);
            if (!$result = $usersPurchasesInsertMapper->setGroup()) {
                throw new ErrorException('Не удалось сохранить данные в БД!');
            }
            return $result;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
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
