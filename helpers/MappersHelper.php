<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\traits\ExceptionsTrait;
use app\mappers\{ColorsMapper, 
    ColorsByIdMapper, 
    SizesMapper, 
    SizesByIdMapper, 
    BrandsMapper, 
    CurrencyMapper, 
    CategoriesMapper, 
    CategoriesBySeocodeMapper, 
    EmailsByEmailMapper, 
    EmailsInsertMapper, 
    AddressByAddressMapper, 
    AddressByIdMapper, 
    AddressInsertMapper, 
    DeliveriesByIdMapper, 
    PaymentsByIdMapper, 
    PhonesByPhoneMapper, 
    PhonesInsertMapper, 
    UsersUpdateMapper, 
    UsersInsertMapper, 
    PurchasesInsertMapper, 
    PurchasesForUserMapper, 
    UsersRulesInsertMapper, 
    CurrencyByIdMapper, 
    CommentsInsertMapper, 
    ProductDetailMapper, 
    ProductsListMapper, 
    ProductsSearchMapper,
    UsersByIdEmailsMapper, 
    UsersByIdMapper,
    DeliveriesMapper, 
    RulesMapper, 
    EmailsByIdMapper, 
    PhonesByIdMapper, 
    CurrencyByMainMapper, 
    SubcategoryForCategoryMapper, 
    ColorsForProductMapper, 
    SizesForProductMapper, 
    SimilarProductsMapper, 
    RelatedProductsMapper, 
    CommentsForProductMapper, 
    PaymentsMapper, 
    ProductsByCodeMapper, 
    ProductsByIdMapper,
    CategoriesByIdMapper, 
    SubcategoryByIdMapper, 
    SubcategoryBySeocodeMapper, 
    ProductsBrandsInsertMapper, 
    ProductsColorsInsertMapper, 
    ProductsSizesInsertMapper, 
    ProductsInsertMapper,
    MailingListMapper,
    EmailsMailingListInsertMapper,
    MailingListByIdMapper};
use app\models\{AddressModel, 
    EmailsModel, 
    PaymentsModel, 
    PhonesModel, 
    UsersModel, 
    DeliveriesModel, 
    CurrencyModel, 
    CommentsModel, 
    ProductsModel, 
    CategoriesModel, 
    SubcategoryModel, 
    BrandsModel, 
    ColorsModel, 
    SizesModel,
    MailingListModel};

/**
 * Коллекция методов для работы с БД
 */
class MappersHelper
{
    use ExceptionsTrait;
    
    /**
     * @var array реестр загруженных объектов
     */
    private static $_objectRegistry = array();
    
    /**
     * Получает массив объектов категорий
     * @return array of objects CategoriesModel
     */
    public static function getCategoriesList()
    {
        try {
            $categoriesMapper = new CategoriesMapper([
                'tableName'=>'categories',
                'fields'=>['id', 'name', 'seocode'],
                'orderByField'=>'name'
            ]);
            $hash = self::createHash([
                CategoriesMapper::className(), 
                $categoriesMapper->tableName, 
                implode('', $categoriesMapper->fields), 
                $categoriesMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $categoriesArray = $categoriesMapper->getGroup();
            if (!is_array($categoriesArray) || empty($categoriesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $categoriesArray);
            return $categoriesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект CategoriesModel по id
     * @param object $categoriesModel экземпляр CategoriesModel
     * @return objects CategoriesModel
     */
    public static function getCategoriesById(CategoriesModel $categoriesModel)
    {
        try {
            $categoriesByIdMapper = new CategoriesByIdMapper([
                'tableName'=>'categories',
                'fields'=>['id', 'name', 'seocode'],
                'model'=>$categoriesModel,
            ]);
            $hash = self::createHash([
                CategoriesByIdMapper::className(), 
                $categoriesByIdMapper->tableName, 
                implode('', $categoriesByIdMapper->fields), 
                $categoriesByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $categoriesModel = $categoriesByIdMapper->getOneFromGroup();
            if (!is_object($categoriesModel) && !$categoriesModel instanceof CategoriesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $categoriesModel);
            return $categoriesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект CategoriesModel по seocode
     * @param object $categoriesModel экземпляр CategoriesModel
     * @return objects CategoriesModel
     */
    public static function getCategoriesBySeocode(CategoriesModel $categoriesModel)
    {
        try {
            $categoriesBySeocodeMapper = new CategoriesBySeocodeMapper([
                'tableName'=>'categories',
                'fields'=>['id', 'name', 'seocode'],
                'model'=>$categoriesModel
            ]);
            $hash = self::createHash([
                CategoriesBySeocodeMapper::className(), 
                $categoriesBySeocodeMapper->tableName, 
                implode('', $categoriesBySeocodeMapper->fields), 
                $categoriesBySeocodeMapper->model->seocode,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $categoriesModel = $categoriesBySeocodeMapper->getOneFromGroup();
            if (!is_object($categoriesModel) && !$categoriesModel instanceof CategoriesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $categoriesModel);
            return $categoriesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов валют
     * @return array of objects CurrencyModel
     */
    public static function getСurrencyList()
    {
        try {
            $currencyMapper = new CurrencyMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency', 'exchange_rate', 'main'],
                'orderByField'=>'currency'
            ]);
            $hash = self::createHash([
                CurrencyMapper::className(), 
                $currencyMapper->tableName, 
                implode('', $currencyMapper->fields), 
                $currencyMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $currencyArray = $currencyMapper->getGroup();
            if (!is_array($currencyArray) || empty($currencyArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $currencyArray);
            return $currencyArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов colors
     * @param boolean $joinProducts определяет, выбирать ли только те записи, которые связаны с хотя бы одним продуктом
     * @return array of objects ColorsModel
     */
    public static function getColorsList($joinProducts=true)
    {
        try {
            $colorsMapper = new ColorsMapper([
                'tableName'=>'colors',
                'fields'=>['id', 'color'],
                'orderByField'=>'color',
            ]);
            if (!$joinProducts) {
                $colorsMapper->queryClass = 'app\queries\ColorsQueryCreator';
            }
            $hash = self::createHash([
                ColorsMapper::className(), 
                $colorsMapper->tableName, 
                implode('', $colorsMapper->fields), 
                $colorsMapper->orderByField,
                $colorsMapper->queryClass,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $colorsArray = $colorsMapper->getGroup();
            if (!is_array($colorsArray) || empty($colorsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $colorsArray);
            return $colorsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает ColorsModel по id
     * @param object $colorsModel экземпляр ColorsModel
     * @return object ColorsModel
     */
    public static function getColorsById(ColorsModel $colorsModel)
    {
        try {
            $colorsByIdMapper = new ColorsByIdMapper([
                'tableName'=>'colors',
                'fields'=>['id', 'color'],
                'model'=>$colorsModel,
            ]);
            $hash = self::createHash([
                ColorsByIdMapper::className(), 
                $colorsByIdMapper->tableName, 
                implode('', $colorsByIdMapper->fields), 
                $colorsByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $colorsModel = $colorsByIdMapper->getOneFromGroup();
            if (!is_object($colorsModel) && !$colorsModel instanceof ColorsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $colorsModel);
            return $colorsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов colors для текущего ProductsModel по id
     * @param object $productsModel экземпляр ProductsModel
     * @return array of objects ColorsModel
     */
    public static function getColorsForProductList(ProductsModel $productsModel)
    {
        try {
            $colorsMapper = new ColorsForProductMapper([
                'tableName'=>'colors',
                'fields'=>['id', 'color'],
                'orderByField'=>'color',
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                ColorsForProductMapper::className(), 
                $colorsMapper->tableName, 
                implode('', $colorsMapper->fields), 
                $colorsMapper->orderByField,
                $colorsMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $colorsArray = $colorsMapper->getGroup();
            if (!is_array($colorsArray) || empty($colorsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $colorsArray);
            return $colorsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов sizes
     * @param boolean $joinProducts определяет, выбирать ли только те записи, которые связаны с хотя бы одним продуктом
     * @return array of objects SizesModel
     */
    public static function getSizesList($joinProducts=true)
    {
        try {
            $sizesMapper = new SizesMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'orderByField'=>'size'
            ]);
            if (!$joinProducts) {
                $sizesMapper->queryClass = 'app\queries\SizesQueryCreator';
            }
            $hash = self::createHash([
                SizesMapper::className(), 
                $sizesMapper->tableName, 
                implode('', $sizesMapper->fields), 
                $sizesMapper->orderByField,
                $sizesMapper->queryClass,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $sizesArray = $sizesMapper->getGroup();
            if (!is_array($sizesArray) || empty($sizesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $sizesArray);
            return $sizesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает SizesModel по id
     * @param object $sizesModel экземпляр SizesModel
     * @return object SizesModel
     */
    public static function getSizesById(SizesModel $sizesModel)
    {
         try {
            $sizesByIdMapper = new SizesByIdMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'model'=>$sizesModel,
            ]);
            $hash = self::createHash([
                SizesByIdMapper::className(), 
                $sizesByIdMapper->tableName, 
                implode('', $sizesByIdMapper->fields), 
                $sizesByIdMapper->model->id
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $sizesModel = $sizesByIdMapper->getOneFromGroup();
            if (!is_object($sizesModel) && !$sizesModel instanceof SizesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $sizesModel);
            return $sizesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов sizes для текущего ProductsModel по id
     * @param object $productsModel экземпляр ProductsModel
     * @return array of objects SizesModel
     */
    public static function getSizesForProductList(ProductsModel $productsModel)
    {
        try {
            $sizesForProductMapper = new SizesForProductMapper([
                'tableName'=>'sizes',
                'fields'=>['id', 'size'],
                'orderByField'=>'size',
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                SizesForProductMapper::className(), 
                $sizesForProductMapper->tableName, 
                implode('', $sizesForProductMapper->fields), 
                $sizesForProductMapper->orderByField,
                $sizesForProductMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $sizesArray = $sizesForProductMapper->getGroup();
            if (!is_array($sizesArray) || empty($sizesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $sizesArray);
            return $sizesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов brands
     * @param boolean $joinProducts определяет, выбирать ли только те записи, которые связаны с хотя бы одним продуктом
     * @return array of objects BrandsModel
     */
    public static function getBrandsList($joinProducts=true)
    {
        try {
            $brandsMapper = new BrandsMapper([
                'tableName'=>'brands',
                'fields'=>['id', 'brand'],
                'orderByField'=>'brand',
            ]);
            if (!$joinProducts) {
                $brandsMapper->queryClass = 'app\queries\BrandsQueryCreator';
            }
            $hash = self::createHash([
                BrandsMapper::className(), 
                $brandsMapper->tableName, 
                implode('', $brandsMapper->fields), 
                $brandsMapper->orderByField,
                $brandsMapper->queryClass,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $brandsArray = $brandsMapper->getGroup();
            if (!is_array($brandsArray) || empty($brandsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $brandsArray);
            return $brandsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект AddressModel по address
     * @return objects AddressModel
     */
    public static function getAddressByAddress(AddressModel $addressModel)
    {
        try {
            $addressByAddressMapper = new AddressByAddressMapper([
                'tableName'=>'address',
                'fields'=>['id', 'address', 'city', 'country', 'postcode'],
                'model'=>$addressModel
            ]);
            $hash = self::createHash([
                AddressByAddressMapper::className(), 
                $addressByAddressMapper->tableName, 
                implode('', $addressByAddressMapper->fields), 
                $addressByAddressMapper->model->address,
                $addressByAddressMapper->model->city,
                $addressByAddressMapper->model->country,
                $addressByAddressMapper->model->postcode,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $addressModel = $addressByAddressMapper->getOneFromGroup();
            if (!is_object($addressModel) && !$addressModel instanceof AddressModel) {
                return null;
            }
            self::createRegistryEntry($hash, $addressModel);
            return $addressModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись AddressModel в БД
     * @param object $addressModel экземпляр AddressModel
     * @return int
     */
    public static function setAddressInsert(AddressModel $addressModel)
    {
        try {
            $addressInsertMapper = new AddressInsertMapper([
                'tableName'=>'address',
                'fields'=>['address', 'city', 'country', 'postcode'],
                'objectsArray'=>[$addressModel],
            ]);
            $result = $addressInsertMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект AddressModel по id
     * @param object $addressModel экземпляр AddressModel
     * @return objects AddressModel
     */
    public static function getAddressById(AddressModel $addressModel)
    {
        try {
            $addressByIdMapper = new AddressByIdMapper([
                'tableName'=>'address',
                'fields'=>['id', 'address', 'city', 'country', 'postcode'],
                'model'=>$addressModel,
            ]);
            $hash = self::createHash([
                AddressByIdMapper::className(), 
                $addressByIdMapper->tableName, 
                implode('', $addressByIdMapper->fields), 
                $addressByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $addressModel = $addressByIdMapper->getOneFromGroup();
            if (!is_object($addressModel) || !$addressModel instanceof AddressModel) {
                return null;
            }
            self::createRegistryEntry($hash, $addressModel);
            return $addressModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект PhonesModel по phone
     * @return objects PhonesModel
     */
    public static function getPhonesByPhone(PhonesModel $phonesModel)
    {
        try {
            $phonesByPhoneMapper = new PhonesByPhoneMapper([
                'tableName'=>'phones',
                'fields'=>['id', 'phone'],
                'model'=>$phonesModel
            ]);
            $hash = self::createHash([
                PhonesByPhoneMapper::className(), 
                $phonesByPhoneMapper->tableName, 
                implode('', $phonesByPhoneMapper->fields), 
                $phonesByPhoneMapper->model->phone,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $phonesModel = $phonesByPhoneMapper->getOneFromGroup();
            if (!is_object($phonesModel) && !$phonesModel instanceof PhonesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $phonesModel);
            return $phonesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись PhonesModel в БД
     * @param object $phonesModel экземпляр PhonesModel
     * @return int
     */
    public static function setPhonesInsert(PhonesModel $phonesModel)
    {
        try {
           $phonesInsertMapper = new PhonesInsertMapper([
                'tableName'=>'phones',
                'fields'=>['phone'],
                'objectsArray'=>[$phonesModel],
            ]);
            $result = $phonesInsertMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает PhonesModel по id
     * @param object $phonesModel экземпляр PhonesModel
     * @return object
     */
    public static function getPhonesById(PhonesModel $phonesModel)
    {
        try {
            $phonesByIdMapper = new PhonesByIdMapper([
                'tableName'=>'phones',
                'fields'=>['id', 'phone'],
                'model'=>$phonesModel,
            ]);
            $hash = self::createHash([
                PhonesByIdMapper::className(), 
                $phonesByIdMapper->tableName, 
                implode('', $phonesByIdMapper->fields), 
                $phonesByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $phonesModel = $phonesByIdMapper->getOneFromGroup();
            if (!is_object($phonesModel) || !$phonesModel instanceof PhonesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $phonesModel);
            return $phonesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
      * Получает DeliveriesModel по id
     * @param object $deliveriesModel экземпляр DeliveriesModel
     * @return object
     */
    public static function getDeliveriesById(DeliveriesModel $deliveriesModel)
    {
        try {
            $deliveriesByIdMapper = new DeliveriesByIdMapper([
                'tableName'=>'deliveries',
                'fields'=>['id', 'name', 'description', 'price'],
                'model'=>$deliveriesModel,
            ]);
            $hash = self::createHash([
                DeliveriesByIdMapper::className(), 
                $deliveriesByIdMapper->tableName, 
                implode('', $deliveriesByIdMapper->fields), 
                $deliveriesByIdMapper->model->id
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $deliveriesModel = $deliveriesByIdMapper->getOneFromGroup();
            if (!is_object($deliveriesModel) || !$deliveriesModel instanceof DeliveriesModel) {
                return null;
            }
            self::createRegistryEntry($hash, $deliveriesModel);
            return $deliveriesModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов deliveries
     * @return array of objects DeliveriesModel
     */
    public static function getDeliveriesList()
    {
        try {
            $deliveriesMapper = new DeliveriesMapper([
                'tableName'=>'deliveries',
                'fields'=>['id', 'name', 'description', 'price'],
                'orderByField'=>'id'
            ]);
            $hash = self::createHash([
                DeliveriesMapper::className(), 
                $deliveriesMapper->tableName, 
                implode('', $deliveriesMapper->fields), 
                $deliveriesMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $deliveriesArray = $deliveriesMapper->getGroup();
            if (!is_array($deliveriesArray) || empty($deliveriesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $deliveriesArray);
            return $deliveriesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает PaymentsModel по id
     * @param object $paymentsModel экземпляр PaymentsModel
     * @return object
     */
    public static function getPaymentsById(PaymentsModel $paymentsModel)
    {
        try {
            $paymentsByIdMapper = new PaymentsByIdMapper([
                'tableName'=>'payments',
                'fields'=>['id', 'name', 'description'],
                'model'=>$paymentsModel,
            ]);
            $hash = self::createHash([
                PaymentsByIdMapper::className(), 
                $paymentsByIdMapper->tableName, 
                implode('', $paymentsByIdMapper->fields), 
                $paymentsByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $paymentsModel = $paymentsByIdMapper->getOneFromGroup();
            if (!is_object($paymentsModel) || !$paymentsModel instanceof PaymentsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $paymentsModel);
            return $paymentsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов payments
     * @return array of objects PaymentsModel
     */
    public static function getPaymentsList()
    {
        try {
            $paymentsMapper = new PaymentsMapper([
                'tableName'=>'payments',
                'fields'=>['id', 'name', 'description'],
            ]);
            $hash = self::createHash([
                PaymentsMapper::className(), 
                $paymentsMapper->tableName, 
                implode('', $paymentsMapper->fields), 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $paymentsArray = $paymentsMapper->getGroup();
            if (!is_array($paymentsArray) || empty($paymentsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $paymentsArray);
            return $paymentsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись PurchasesModel в БД, вязывающую пользователя с купленным товаром
     * @return boolean
     */
    public static function setPurchasesInsert()
    {
        try {
            $id_users = \Yii::$app->cart->user->id;
            $productsArray = \Yii::$app->cart->getProductsArray();
            $id_deliveries = \Yii::$app->cart->user->deliveries->id;
            $id_payments = \Yii::$app->cart->user->payments->id;
            
            if (empty($id_users)) {
                throw new ErrorException('Отсутствует cart->user->id!');
            }
            if (!is_array($productsArray) || empty($productsArray)) {
                throw new ErrorException('Отсутствуют данные в массиве cart->productsArray!');
            }
            if (empty($id_deliveries)) {
                throw new ErrorException('Отсутствует user->deliveries->id!');
            }
            if (empty($id_payments)) {
                throw new ErrorException('Отсутствует user->payments->id!');
            }
            
            $arrayToDb = [];
            foreach ($productsArray as $product) {
                $arrayToDb[] = ['id_users'=>$id_users, 'id_products'=>$product->id, 'quantity'=>$product->quantity, 'id_colors'=>$product->colorToCart, 'id_sizes'=>$product->sizeToCart, 'id_deliveries'=>$id_deliveries, 'id_payments'=>$id_payments, 'received'=>true];
            }
            
            $usersPurchasesInsertMapper = new PurchasesInsertMapper([
                'tableName'=>'purchases',
                'fields'=>['id_users', 'id_products', 'quantity', 'id_colors', 'id_sizes', 'id_deliveries', 'id_payments', 'received', 'received_date'],
                'DbArray'=>$arrayToDb,
            ]);
            if (!$result = $usersPurchasesInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов purchases для текущего UsersModel по id
     * @param object $usersModel экземпляр UsersModel
     * @return array of objects PurchasesModel
     */
    public static function getPurchasesForUserList(UsersModel $usersModel)
    {
        try {
            $purchasesForUserMapper = new PurchasesForUserMapper([
                'tableName'=>'purchases',
                'fields'=>['id', 'id_users', 'id_products', 'quantity', 'id_colors', 'id_sizes', 'id_deliveries', 'id_payments', 'received', 'received_date', 'processed', 'canceled', 'shipped'],
                'orderByField'=>'received_date',
                'orderByType'=>'DESC',
                'model'=>$usersModel,
            ]);
            $hash = self::createHash([
                PurchasesForUserMapper::className(), 
                $purchasesForUserMapper->tableName, 
                implode('', $purchasesForUserMapper->fields), 
                $purchasesForUserMapper->orderByField, 
                $purchasesForUserMapper->orderByType, 
                $purchasesForUserMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $purchasesArray = $purchasesForUserMapper->getGroup();
            if (!is_array($purchasesArray) || empty($purchasesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $purchasesArray);
            return $purchasesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обновляет запись в БД объектом UsersModel
     * @return objects UsersModel
     */
    public static function setUsersUpdate(UsersModel $usersModel)
    {
        try {
            $usersUpdateMapper = new UsersUpdateMapper([
                'tableName'=>'users',
                'fields'=>['name', 'surname', 'id_emails', 'id_phones', 'id_address'],
                'model'=>$usersModel,
            ]);
            $result = $usersUpdateMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись UsersModel в БД
     * @param object $usersModel экземпляр UsersModel
     * @return int
     */
    public static function setUsersInsert(UsersModel $usersModel)
    {
        try {
            $usersInsertMapper = new UsersInsertMapper([
                'tableName'=>'users',
                'fields'=>['id_emails', 'password', 'name', 'surname', 'id_phones', 'id_address'],
                'objectsArray'=>[$usersModel],
            ]);
            $result = $usersInsertMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект EmailsModel по email
     * @return objects EmailsModel
     */
    public static function getEmailsByEmail(EmailsModel $emailsModel)
    {
        try {
            $emailsByEmailMapper = new EmailsByEmailMapper([
                'tableName'=>'emails',
                'fields'=>['id', 'email'],
                'model'=>$emailsModel
            ]);
            $hash = self::createHash([
                EmailsByEmailMapper::className(), 
                $emailsByEmailMapper->tableName, 
                implode('', $emailsByEmailMapper->fields), 
                $emailsByEmailMapper->model->email, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $emailsModel = $emailsByEmailMapper->getOneFromGroup();
            if (!is_object($emailsModel) && !$emailsModel instanceof EmailsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $emailsModel);
            return $emailsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись EmailsModel в БД
     * @param object $emailsModel экземпляр EmailsModel
     * @return int
     */
    public static function setEmailsInsert(EmailsModel $emailsModel)
    {
        try {
            $emailsInsertMapper = new EmailsInsertMapper([
                'tableName'=>'emails',
                'fields'=>['email'],
                'objectsArray'=>[$emailsModel],
            ]);
            $result = $emailsInsertMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает EmailsModel по id
     * @param object $emailsModel экземпляр EmailsModel
     * @return object
     */
    public static function getEmailsById(EmailsModel $emailsModel)
    {
        try {
            $emailsByIdMapper = new EmailsByIdMapper([
                'tableName'=>'emails',
                'fields'=>['id', 'email'],
                'model'=>$emailsModel,
            ]);
            $hash = self::createHash([
                EmailsByIdMapper::className(), 
                $emailsByIdMapper->tableName, 
                implode('', $emailsByIdMapper->fields), 
                $emailsByIdMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $emailsModel = $emailsByIdMapper->getOneFromGroup();
            if (!is_object($emailsModel) || !$emailsModel instanceof EmailsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $emailsModel);
            return $emailsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись в БД, связывающую пользователя с правами доступа
     * @param object $usersModel экземпляр UsersModel
     * @return int
     */
    public static function setUsersRulesInsert(UsersModel $usersModel)
    {
        try {
            $usersRulesInsertMapper = new UsersRulesInsertMapper([
                'tableName'=>'users_rules',
                'fields'=>['id_users', 'id_rules'],
                'model'=>$usersModel
            ]);
            if (!$result = $usersRulesInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект CurrencyModel по id
     * @param object $currencyModel экземпляр CurrencyModel
     * @return objects CurrencyModel
     */
    public static function getCurrencyById(CurrencyModel $currencyModel)
    {
        try {
            $currencyByIdMapper = new CurrencyByIdMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency', 'exchange_rate', 'main'],
                'model'=>$currencyModel,
            ]);
            $hash = self::createHash([
                CurrencyByIdMapper::className(), 
                $currencyByIdMapper->tableName, 
                implode('', $currencyByIdMapper->fields), 
                $currencyByIdMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $currencyModel = $currencyByIdMapper->getOneFromGroup();
            if (!is_object($currencyModel) && !$currencyModel instanceof CurrencyModel) {
                return null;
            }
            self::createRegistryEntry($hash, $currencyModel);
            return $currencyModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект CurrencyModel по main
     * @param object $currencyModel экземпляр CurrencyModel
     * @return objects CurrencyModel
     */
    public static function getCurrencyByMain()
    {
        try {
            $currencyByMainMapper = new CurrencyByMainMapper([
                'tableName'=>'currency',
                'fields'=>['id', 'currency', 'exchange_rate', 'main'],
            ]);
            $hash = self::createHash([
                CurrencyByMainMapper::className(), 
                $currencyByMainMapper->tableName, 
                implode('', $currencyByMainMapper->fields), 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $currencyModel = $currencyByMainMapper->getOneFromGroup();
            if (!is_object($currencyModel) || !$currencyModel instanceof CurrencyModel) {
                return null;
            }
            self::createRegistryEntry($hash, $currencyModel);
            return $currencyModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись комментария в БД
     * @param object $commentsModel экземпляр CommentsModel
     * @return int
     */
    public static function setCommentsInsert(CommentsModel $commentsModel)
    {
        try {
            $commentsInsertMapper = new CommentsInsertMapper([
                'tableName'=>'comments',
                'fields'=>['text', 'name', 'id_emails', 'id_products'],
                'objectsArray'=>[$commentsModel],
            ]);
            if (!$result = $commentsInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив ProductsModel
     * @param array $config массив настроек для маппера
     * @return array of objects ProductsModel
     */
    public static function getProductsList($config)
    {
        try {
            $productsMapper = new ProductsListMapper($config);
            $hash = self::createHash([
                ProductsListMapper::className(), 
                $productsMapper->tableName, 
                implode('', $productsMapper->fields), 
                serialize($productsMapper->otherTablesFields), 
                $productsMapper->orderByField, 
                $productsMapper->getDataSorting, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $productsArray = $productsMapper->getGroup();
            if (!is_array($productsArray) || empty($productsArray) || !$productsArray[0] instanceof ProductsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $productsArray);
            return $productsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив ProductsModel при поиске
     * @param array $config массив настроек для маппера
     * @return array of objects ProductsModel
     */
    public static function getProductsSearch($config)
    {
        try {
            $productsSearchMapper = new ProductsSearchMapper($config);
            $hash = self::createHash([
                ProductsSearchMapper::className(), 
                $productsSearchMapper->tableName, 
                implode('', $productsSearchMapper->fields), 
                $productsSearchMapper->orderByField, 
                $productsSearchMapper->orderByType, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $productsArray = $productsSearchMapper->getGroup();
            if (!is_array($productsArray) || empty($productsArray) || !$productsArray[0] instanceof ProductsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $productsArray);
            return $productsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект ProductsModel по code
     * @param object $productsModel экземпляр ProductsModel
     * @return objects ProductsModel
     */
    public static function getProductsByCode(ProductsModel $productsModel)
    {
        try {
            $productsByCodeMapper = new ProductsByCodeMapper([
                'tableName'=>'products',
                'fields'=>['id', 'date', 'code', 'name', 'description', 'price', 'images'],
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                ProductsByCodeMapper::className(), 
                $productsByCodeMapper->tableName, 
                implode('', $productsByCodeMapper->fields), 
                $productsByCodeMapper->model->code, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $productsModel = $productsByCodeMapper->getOneFromGroup();
            if (!is_object($productsModel) && !$productsModel instanceof ProductsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $productsModel);
            return $productsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект ProductsModel по id
     * @param object $productsModel экземпляр ProductsModel
     * @return objects ProductsModel
     */
    public static function getProductsById(ProductsModel $productsModel)
    {
        try {
            $productsByIdMapper = new ProductsByIdMapper([
                'tableName'=>'products',
                'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory'],
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                ProductsByIdMapper::className(), 
                $productsByIdMapper->tableName, 
                implode('', $productsByIdMapper->fields), 
                $productsByIdMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $productsModel = $productsByIdMapper->getOneFromGroup();
            if (!is_object($productsModel) && !$productsModel instanceof ProductsModel) {
                return null;
            }
            self::createRegistryEntry($hash, $productsModel);
            return $productsModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись ProductsModel в БД
     * @param object $productsModel экземпляр ProductsModel
     * @return int
     */
    public static function setProductsInsert(ProductsModel $productsModel)
    {
        try {
            $productsInsertMapper = new ProductsInsertMapper([
                'tableName'=>'products',
                'fields'=>['date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory'],
                'objectsArray'=>[$productsModel],
            ]);
            $result = $productsInsertMapper->setGroup();
            if (!$result) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект UsersModel по id_emails
     * @param object $usersModel экземпляр UsersModel
     * @return objects UsersModel
     */
    public static function getUsersByIdEmails(UsersModel $usersModel)
    {
        try {
            $usersByLoginMapper = new UsersByIdEmailsMapper([
                'tableName'=>'users',
                'fields'=>['id', 'id_emails', 'password', 'name', 'surname', 'id_phones', 'id_address'],
                'model'=>$usersModel
            ]);
            $hash = self::createHash([
                UsersByIdEmailsMapper::className(), 
                $usersByLoginMapper->tableName, 
                implode('', $usersByLoginMapper->fields), 
                $usersByLoginMapper->model->id_emails, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $usersModel = $usersByLoginMapper->getOneFromGroup();
            if (!is_object($usersModel) || !$usersModel instanceof UsersModel) {
                return null;
            }
            self::createRegistryEntry($hash, $usersModel);
            return $usersModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект UsersModel по id
     * @param object $usersModel экземпляр UsersModel
     * @return objects UsersModel
     */
    public static function getUsersById(UsersModel $usersModel)
    {
        try {
            $usersByIdMapper = new UsersByIdMapper([
                'tableName'=>'users',
                'fields'=>['id', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'],
                'model'=>$usersModel,
            ]);
            $hash = self::createHash([
                UsersByIdMapper::className(), 
                $usersByIdMapper->tableName, 
                implode('', $usersByIdMapper->fields), 
                $usersByIdMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $usersModel = $usersByIdMapper->getOneFromGroup();
            if (!is_object($usersModel) || !$usersModel instanceof UsersModel) {
                return null;
            }
            self::createRegistryEntry($hash, $usersModel);
            return $usersModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов rules
     * @return array of objects RulesModel
     */
    public static function getRulesList()
    {
        try {
            $rulesMapper = new RulesMapper([
                'tableName'=>'rules',
                'fields'=>['id', 'rule'],
                'orderByField'=>'rule',
            ]);
            $hash = self::createHash([
                RulesMapper::className(), 
                $rulesMapper->tableName, 
                implode('', $rulesMapper->fields), 
                $rulesMapper->orderByField, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $rulesArray = $rulesMapper->getGroup();
            if (!is_array($rulesArray) || empty($rulesArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $rulesArray);
            return $rulesArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов SubcategoryModel по seocode CategoriesModel
     * @return array of objects SubcategoryModel
     */
    public static function getSubcategoryForCategoryList(CategoriesModel $categoriesModel)
    {
        try {
            $subcategoryForCategoryMapper = new SubcategoryForCategoryMapper([
                'tableName'=>'subcategory',
                'fields'=>['id', 'name', 'seocode', 'id_categories'],
                'model'=>$categoriesModel
            ]);
            $hash = self::createHash([
                SubcategoryForCategoryMapper::className(), 
                $subcategoryForCategoryMapper->tableName, 
                implode('', $subcategoryForCategoryMapper->fields), 
                $subcategoryForCategoryMapper->model->seocode, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $subcategoryArray = $subcategoryForCategoryMapper->getGroup();
            if (!is_array($subcategoryArray) || empty($subcategoryArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $subcategoryArray);
            return $subcategoryArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает объект SubcategoryModel по id
     * @param object $subcategoryModel экземпляр SubcategoryModel
     * @return objects SubcategoryModel
     */
    public static function getSubcategoryById(SubcategoryModel $subcategoryModel)
    {
        try {
            $subcategoryByIdMapper = new SubcategoryByIdMapper([
                'tableName'=>'subcategory',
                'fields'=>['id', 'name', 'seocode', 'id_categories'],
                'model'=>$subcategoryModel,
            ]);
            $hash = self::createHash([
                SubcategoryByIdMapper::className(), 
                $subcategoryByIdMapper->tableName, 
                implode('', $subcategoryByIdMapper->fields), 
                $subcategoryByIdMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $subcategoryModel = $subcategoryByIdMapper->getOneFromGroup();
            if (!is_object($subcategoryModel) || !$subcategoryModel instanceof SubcategoryModel) {
                return null;
            }
            self::createRegistryEntry($hash, $subcategoryModel);
            return $subcategoryModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
     /**
     * Получает объект SubcategoryModel по seocode
     * @param object $subcategoryModel экземпляр SubcategoryModel
     * @return objects SubcategoryModel
     */
    public static function getSubcategoryBySeocode(SubcategoryModel $subcategoryModel)
    {
        try {
            $subcategoryBySeocodeMapper = new SubcategoryBySeocodeMapper([
                'tableName'=>'subcategory',
                'fields'=>['id', 'name', 'seocode', 'id_categories'],
                'model'=>$subcategoryModel,
            ]);
            $hash = self::createHash([
                SubcategoryBySeocodeMapper::className(), 
                $subcategoryBySeocodeMapper->tableName, 
                implode('', $subcategoryBySeocodeMapper->fields), 
                $subcategoryBySeocodeMapper->model->seocode, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $subcategoryModel = $subcategoryBySeocodeMapper->getOneFromGroup();
            if (!is_object($subcategoryModel) || !$subcategoryModel instanceof SubcategoryModel) {
                return null;
            }
            self::createRegistryEntry($hash, $subcategoryModel);
            return $subcategoryModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов products, похожих свойствами с текущим ProductsModel 
     * @return array of objects ProductsModel
     */
    public static function getSimilarProductsList(ProductsModel $productsModel)
    {
        try {
            $similarProductsMapper = new SimilarProductsMapper([
                'tableName'=>'products',
                'fields'=>['id', 'date', 'name', 'price', 'images'],
                'orderByField'=>'date',
                'getDataSorting'=>false,
                'otherTablesFields'=>[
                    ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                    ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
                ],
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                SimilarProductsMapper::className(), 
                $similarProductsMapper->tableName, 
                implode('', $similarProductsMapper->fields), 
                $similarProductsMapper->orderByField, 
                serialize($similarProductsMapper->otherTablesFields),
                $similarProductsMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $similarsArray = $similarProductsMapper->getGroup();
            if (!is_array($similarsArray) || empty($similarsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $similarsArray);
            return $similarsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов ProductsModel, связанных с текущим ProductsModel по id
     * @return array of objects ProductsModel
     */
    public static function getRelatedProductsList(ProductsModel $productsModel)
    {
        try {
            $relatedProductsMapper = new RelatedProductsMapper([
                'tableName'=>'products',
                'fields'=>['id', 'date', 'name', 'price', 'images'],
                'orderByField'=>'date',
                'getDataSorting'=>false,
                'otherTablesFields'=>[
                    ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                    ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
                ],
                'orderByField'=>'date',
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                RelatedProductsMapper::className(), 
                $relatedProductsMapper->tableName, 
                implode('', $relatedProductsMapper->fields), 
                $relatedProductsMapper->orderByField, 
                serialize($relatedProductsMapper->otherTablesFields),
                $relatedProductsMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $relatedArray = $relatedProductsMapper->getGroup();
            if (!is_array($relatedArray) || empty($relatedArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $relatedArray);
            return $relatedArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов comments для текущего ProductsModel по id
     * @param object $productsModel экземпляр ProductsModel
     * @return array of objects CommentsModel
     */
    public static function getCommentsForProductList(ProductsModel $productsModel)
    {
        try {
            $commentsForProductMapper = new CommentsForProductMapper([
                'tableName'=>'comments',
                'fields'=>['id', 'text', 'name', 'id_emails', 'id_products', 'active'],
                'model'=>$productsModel,
            ]);
            $hash = self::createHash([
                CommentsForProductMapper::className(), 
                $commentsForProductMapper->tableName, 
                implode('', $commentsForProductMapper->fields), 
                $commentsForProductMapper->model->id, 
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $commentsArray = $commentsForProductMapper->getGroup();
            if (!is_array($commentsArray) || empty($commentsArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $commentsArray);
            return $commentsArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись ProductsBrandsModel в БД, связывающую товар с брендом
     * @param object $productsModel экземпляр ProductsModel
     * @param object $brandsModel экземпляр BrandsModel
     * @return boolean
     */
    public static function setProductsBrandsInsert(ProductsModel $productsModel, BrandsModel $brandsModel)
    {
        try {
            $productsBrandsInsertMapper = new ProductsBrandsInsertMapper([
                'tableName'=>'products_brands',
                'fields'=>['id_products', 'id_brands'],
                'DbArray'=>[['id_products'=>$productsModel->id, 'id_brands'=>$brandsModel->id]],
            ]);
            if (!$result = $productsBrandsInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись ProductsColorsModel в БД, связывающую товар с colors
     * @param object $productsModel экземпляр ProductsModel
     * @param object $colorsModel экземпляр ColorsModel
     * @return boolean
     */
    public static function setProductsColorsInsert(ProductsModel $productsModel, ColorsModel $colorsModel)
    {
        try {
            if (!is_array($colorsModel->idArray) || empty($colorsModel->idArray)) {
                throw new ErrorException('Отсутствуют данные для выполнения запроса!');
            }
            $arrayToDb = [];
            foreach ($colorsModel->idArray as $colorId) {
                $arrayToDb[] = ['id_products'=>$productsModel->id, 'id_colors'=>$colorId];
            }
            $productsColorsInsertMapper = new ProductsColorsInsertMapper([
                'tableName'=>'products_colors',
                'fields'=>['id_products', 'id_colors'],
                'DbArray'=>$arrayToDb,
            ]);
            if (!$result = $productsColorsInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
     
     /**
     * Создает новую запись ProductsSizesModel в БД, связывающую товар с colors
     * @param object $productsModel экземпляр ProductsModel
     * @param object $sizesModel экземпляр SizesModel
     * @return boolean
     */
    public static function setProductsSizesInsert(ProductsModel $productsModel, SizesModel $sizesModel)
    {
        try {
            if (!is_array($sizesModel->idArray) || empty($sizesModel->idArray)) {
                throw new ErrorException('Отсутствуют данные для выполнения запроса!');
            }
            $arrayToDb = [];
            foreach ($sizesModel->idArray as $sizeId) {
                $arrayToDb[] = ['id_products'=>$productsModel->id, 'id_sizes'=>$sizeId];
            }
            $productsSizesInsertMapper = new ProductsSizesInsertMapper([
                'tableName'=>'products_sizes',
                'fields'=>['id_products', 'id_sizes'],
                'DbArray'=>$arrayToDb,
            ]);
            if (!$result = $productsSizesInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает массив объектов MailingListModel
     * @return array of objects MailingListModel
     */
    public static function getMailingList()
    {
        try {
            $mailingListMapper = new MailingListMapper([
                'tableName'=>'mailing_list',
                'fields'=>['id', 'name', 'description'],
                'orderByField'=>'name',
            ]);
            $hash = self::createHash([
                MailingListMapper::className(), 
                $mailingListMapper->tableName, 
                implode('', $mailingListMapper->fields), 
                $mailingListMapper->orderByField,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $mailingListArray = $mailingListMapper->getGroup();
            if (!is_array($mailingListArray) || empty($mailingListArray)) {
                return null;
            }
            self::createRegistryEntry($hash, $mailingListArray);
            return $mailingListArray;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Создает новую запись EmailsMailingListModel в БД, связывающую email с рассылками
     * @param object $emailsModel экземпляр EmailsModel
     * @param object $mailingListModel экземпляр MailingListModel
     * @return boolean
     */
    public static function setEmailsMailingListInsert(EmailsModel $emailsModel, MailingListModel $mailingListModel)
    {
        try {
            if (!is_array($mailingListModel->idFromForm) || empty($mailingListModel->idFromForm)) {
                throw new ErrorException('Отсутствуют данные для выполнения запроса!');
            }
            $arrayToDb = [];
            foreach ($mailingListModel->idFromForm as $mailingListId) {
                $arrayToDb[] = ['id_email'=>$emailsModel->id, 'id_mailing_list'=>$mailingListId];
            }
            $emailsMailingListInsertMapper = new EmailsMailingListInsertMapper([
                'tableName'=>'emails_mailing_list',
                'fields'=>['id_email', 'id_mailing_list'],
                'DbArray'=>$arrayToDb,
            ]);
            if (!$result = $emailsMailingListInsertMapper->setGroup()) {
                return null;
            }
            return $result;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Получает MailingListModel по id
     * @param object $mailingListModel экземпляр MailingListModel
     * @return object MailingListModel
     */
    public static function getMailingListById(MailingListModel $mailingListModel)
    {
        try {
            $mailingListByIdMapper = new MailingListByIdMapper([
                'tableName'=>'mailing_list',
                'fields'=>['id', 'name', 'description'],
                'model'=>$mailingListModel,
            ]);
            $hash = self::createHash([
                MailingListByIdMapper::className(), 
                $mailingListByIdMapper->tableName, 
                implode('', $mailingListByIdMapper->fields), 
                $mailingListByIdMapper->model->id,
            ]);
            if (self::compareHashes($hash)) {
                return self::$_objectRegistry[$hash];
            }
            $mailingListModel = $mailingListByIdMapper->getOneFromGroup();
            if (!is_object($mailingListModel) && !$mailingListModel instanceof MailingListModel) {
                return null;
            }
            self::createRegistryEntry($hash, $mailingListModel);
            return $mailingListModel;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обнуляет значение всех свойств класса
     * @return boolean
     */
    public static function cleanProperties()
    {
        try {
            self::$_objectRegistry = array();
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Сравнивает хеш текущего объекта с хешами-ключами в MappersHelper::$_objectRegistry, 
     * возвращает true, если совпадение найдено, иначе false
     * @params string хеш
     * @return boolean
     */
    private static function compareHashes(string $hash)
    {
        try {
            if (!array_key_exists($hash, self::$_objectRegistry)) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Сохраняет загруженные данные в реестре MappersHelper::$_objectRegistry
     * @param string $hash хеш сохраняемого объекта, который станет ключом в реестре
     * @param object $object объект, который необходимо сохранить в реестре
     * @return boolean
     */
    private static function createRegistryEntry(string $hash, $object)
    {
        try {
            self::$_objectRegistry[$hash] = $object;
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Конструирует хеш с помощью функции md5
     * @param array $inputArray массив данных для конструирования хеша
     * @return string результирующий хеш
     */
    private static function createHash(Array $inputArray)
    {
        try {
            $inputString = implode('-', $inputArray);
            return md5($inputString);
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив реестра MappersHelper::$_objectRegistry
     * @return array
     */
    public static function getObjectRegistry()
    {
        try {
            return self::$_objectRegistry;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}
