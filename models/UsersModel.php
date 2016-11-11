<?php

namespace app\models;

use yii\web\IdentityInterface;
use app\models\{AbstractBaseModel,
    AddressModel,
    CitiesModel,
    CountriesModel,
    EmailsModel,
    NamesModel,
    PhonesModel,
    PostcodesModel,
    SurnamesModel};
use app\exceptions\ExceptionsTrait;

/**
 * Представляет данные таблицы users
 */
class UsersModel extends AbstractBaseModel implements IdentityInterface
{
    /**
     * Сценарий сохранения данных из формы аутентификации
    */
    const GET_FROM_AUTHENTICATION = 'getFromAuthentication';
    /**
     * Сценарий сохранения данных из формы регистрации
    */
    const GET_FROM_REGISTRATION = 'getFromRegistration';
    /**
     * Сценарий сохранения данных из формы заказа
    */
    const GET_FROM_ORDER = 'getFromOrder';
    
    /**
     * @var string имя пользователя, 
     * полученное из БД в процессе оформления покупки
     */
    public $userName;
    /**
     * @var string фамилия пользователя, 
     * полученное из БД в процессе оформления покупки
     */
    public $userSurname;
    /**
     * @var string email пользователя, 
     * полученное из БД в процессе оформления покупки
     */
    public $userEmail;
    /**
     * @var string телефон пользователя, 
     * полученное из БД в процессе оформления покупки
     */
    public $userPhone;
    /**
     * @var string адрес пользователя, 
     * полученное из БД в процессе оформления покупки
     */
    public $userAddress;
    /**
     * @var string город пользователя, 
     * полученное из БД в процессе оформления покупки
     */
    public $userCity;
    /**
     * @var string страна пользователя, 
     * полученное из БД в процессе оформления покупки
     */
    public $userCountry;
    /**
     * @var string почтовый код пользователя, 
     * полученное из БД в процессе оформления покупки
     */
    public $userPostcode;
    
    /**
     * Возвращает имя таблицы, связанной с текущим классом AR
     * @return string
     */
    public static function tableName()
    {
        try {
            return 'users';
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    public function scenarios()
    {
        return [
            self::GET_FROM_AUTHENTICATION=>['password'],
            self::GET_FROM_REGISTRATION=>['password'],
            self::GET_FROM_ORDER=>['id', 'password'],
        ];
    }
    
    public function rules()
    {
        return [
            [['password'], 'app\validators\StripTagsValidator'],
            [['id_name', 'id_surname', 'id_phone', 'id_address'], 'default', 'value'=>0],
            [['password'], 'required', 'on'=>self::GET_FROM_AUTHENTICATION],
            [['password'], 'required', 'on'=>self::GET_FROM_REGISTRATION],
        ];
    }
    
    public function fields()
    {
        return [
            'id'=>'id',
            'id_email'=>'id_email',
            'id_name'=>'id_name',
            'id_surname'=>'id_surname',
            'id_phone'=>'id_phone',
            'id_address'=>'id_address',
            'id_city'=>'id_city',
            'id_country'=>'id_country',
            'id_postcode'=>'id_postcode',
        ];
    }
    
    public function extraFields()
    {
        return [
            'password'=>'password',
        ];
    }
    
    /**
     * Получает объект EmailsModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getEmail()
    {
        try {
            return $this->hasOne(EmailsModel::class, ['id'=>'id_email'])->inverseOf('user');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект NamesModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getName()
    {
        try {
            return $this->hasOne(NamesModel::class, ['id'=>'id_name']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект SurnamesModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getSurname()
    {
        try {
            return $this->hasOne(SurnamesModel::class, ['id'=>'id_surname']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект PhonesModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getPhone()
    {
        try {
            return $this->hasOne(PhonesModel::class, ['id'=>'id_phone']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект AddressModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getAddress()
    {
        try {
            return $this->hasOne(AddressModel::class, ['id'=>'id_address']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект CitiesModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getCity()
    {
        try {
            return $this->hasOne(CitiesModel::class, ['id'=>'id_city']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект CountriesModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getCountry()
    {
        try {
            return $this->hasOne(CountriesModel::class, ['id'=>'id_country']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Получает объект PostcodesModel, с которым связан текущий объект UsersModel
     * @return ActiveQueryInterface the relational query object
     */
    public function getPostcode()
    {
        try {
            return $this->hasOne(PostcodesModel::class, ['id'=>'id_postcode']);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект UsersModel по $id
     * @params int $id 
     * @return mixed UsersModel/null
     */
    public static function findIdentity($id)
    {
        try {
            $usersModel = static::findOne($id);
            if ($usersModel instanceof UsersModel) {
                return $usersModel;
            }
            
            return null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает ID текущего пользователя
     * @return int
     */
    public function getId(): int
    {
        try {
            return $this->id;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public static function findIdentityByAccessToken($token, $type=null)
    {
    }
    
    public function getAuthKey()
    {
    }
    
    public function validateAuthKey($authKey)
    {
    }
}
