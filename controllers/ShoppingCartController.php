<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\helpers\ModelsInstancesHelper;
use app\helpers\MappersHelper;
use app\helpers\MailHelper;
use app\models\ProductsModel;

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
            if (empty(\Yii::$app->cart)) {
                return $this->redirect(Url::to(['products-list/index']));
            }
            $renderArray = array();
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('shopping-cart.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом удаления из корзины определенного продукта
     * @return redirect
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
     * @return redirect
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
            if (empty(\Yii::$app->cart)) {
                return $this->redirect(Url::to(['products-list/index']));
            }
            
            $usersModel = \Yii::$app->cart->user;
            $emailsModel = \Yii::$app->cart->user->emails;
            $addressModel = \Yii::$app->cart->user->address;
            $phonesModel = \Yii::$app->cart->user->phones;
            $deliveriesModel = \Yii::$app->cart->user->deliveries;
            $paymentsModel = \Yii::$app->cart->user->payments;
            
            if (\Yii::$app->user->login != \Yii::$app->params['nonAuthenticatedUserLogin']) {
                if (empty(\Yii::$app->cart->user->name) && !empty(\Yii::$app->user->name)) {
                    $usersModel->name = \Yii::$app->user->name;
                }
                if (empty(\Yii::$app->cart->user->surname) && !empty(\Yii::$app->user->surname)) {
                    $usersModel->surname = \Yii::$app->user->surname;
                }
                if (empty(\Yii::$app->cart->user->emails->email) && !empty(\Yii::$app->user->emails->email)) {
                    $emailsModel->email = \Yii::$app->user->emails->email;
                }
                if (empty(\Yii::$app->cart->user->phones->phone) && !empty(\Yii::$app->user->phones->phone)) {
                    $phonesModel->phone = \Yii::$app->user->phones->phone;
                }
                if (empty(\Yii::$app->cart->user->address->address) && !empty(\Yii::$app->user->address->address)) {
                    $addressModel->address = \Yii::$app->user->address->address;
                }
                if (empty(\Yii::$app->cart->user->address->city) && !empty(\Yii::$app->user->address->city)) {
                    $addressModel->city = \Yii::$app->user->address->city;
                }
                if (empty(\Yii::$app->cart->user->address->postcode) && !empty(\Yii::$app->user->address->postcode)) {
                    $addressModel->postcode = \Yii::$app->user->address->postcode;
                }
                if (empty(\Yii::$app->cart->user->address->country) && !empty(\Yii::$app->user->address->country)) {
                    $addressModel->country = \Yii::$app->user->address->country;
                }
            }
            
            if (\Yii::$app->request->isPost && $usersModel->load(\Yii::$app->request->post()) && $emailsModel->load(\Yii::$app->request->post()) && $addressModel->load(\Yii::$app->request->post()) && $phonesModel->load(\Yii::$app->request->post()) && $deliveriesModel->load(\Yii::$app->request->post()) && $paymentsModel->load(\Yii::$app->request->post())) {
                if ($usersModel->validate() && $emailsModel->validate() && $addressModel->validate() && $phonesModel->validate() && $deliveriesModel->validate() && $paymentsModel->validate()) {
                    
                }
                return $this->redirect(Url::to(['shopping-cart/check-pay']));
            }
            
            $renderArray = array();
            $renderArray['usersModel'] = $usersModel;
            $renderArray['emailsModel'] = $emailsModel;
            $renderArray['addressModel'] = $addressModel;
            $renderArray['phonesModel'] = $phonesModel;
            $renderArray['deliveriesModel'] = $deliveriesModel;
            $renderArray['paymentsModel'] = $paymentsModel;
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('address-contacts.twig', $renderArray);
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
            if (empty(\Yii::$app->cart)) {
                return $this->redirect(Url::to(['products-list/index']));
            } elseif (empty(\Yii::$app->cart->user)) {
                return $this->redirect(Url::to(['shopping-cart/address-contacts']));
            }
            $renderArray = array();
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('check-pay.twig', $renderArray);
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
            if (empty(\Yii::$app->cart)) {
                return $this->redirect(Url::to(['products-list/index']));
            } elseif (empty(\Yii::$app->cart->user)) {
                return $this->redirect(Url::to(['shopping-cart/address-contacts']));
            }
            
            if (!empty(\Yii::$app->cart->user->emails) && is_object(\Yii::$app->cart->user->emails)) {
                if (!$emailsModel = MappersHelper::getEmailsModel(\Yii::$app->cart->user->emails)) {
                    throw new ErrorException('Ошибка при сохранении E-mail!');
                }
                \Yii::$app->cart->user->id_emails = $emailsModel->id;
            } else {
                throw new ErrorException('Недоступны данные для сохранения сведений о покупке!');
            }
            
            if (!empty(\Yii::$app->cart->user->address) && is_object(\Yii::$app->cart->user->address)) {
                if (!$addressModel = MappersHelper::getAddressModel(\Yii::$app->cart->user->address)) {
                    throw new ErrorException('Ошибка при сохранении address!');
                }
                \Yii::$app->cart->user->id_address = $addressModel->id;
            } else {
                throw new ErrorException('Недоступны данные для сохранения сведений о покупке!');
            }
            
            if (!empty(\Yii::$app->cart->user->phones) && is_object(\Yii::$app->cart->user->phones)) {
                if (!$phonesModel = MappersHelper::getPhonesModel(\Yii::$app->cart->user->phones)) {
                    throw new ErrorException('Ошибка при сохранении phones!');
                }
                \Yii::$app->cart->user->id_phones = $phonesModel->id;
            } else {
                throw new ErrorException('Недоступны данные для сохранения сведений о покупке!');
            }
            
            if (MappersHelper::setOrUpdateUsers(\Yii::$app->cart->user)) {
                if (MappersHelper::setUsersPurchases()) {
                    if (!MailHelper::send([['template'=>'@app/views/mail/customer.twig', 'setFrom'=>['test@test.com'=>'John'], 'setTo'=>['timofey@localhost.localdomain'=>'Timofey'], 'setSubject'=>'Hello!']])) {
                        throw new ErrorException('Ошибка при отправке E-mail сообщения!');
                    }
                    if (!\Yii::$app->cart->clearProductsArray()) {
                        throw new ErrorException('Ошибка при очистке корзины!');
                    }
                } else {
                    throw new ErrorException('Ошибка при сохранении связи пользователя с покупкой в процессе оформления заказа!');
                }
            } else {
                throw new ErrorException('Ошибка при сохранении данных пользователя в процессе оформления покупки!');
            }
            
            $renderArray = array();
            $renderArray['email'] = $emailsModel;
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('thank.twig', $renderArray);
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
