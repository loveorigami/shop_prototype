<?php

namespace app\helpers;

use app\traits\ExceptionsTrait;
use yii\base\ErrorException;
use app\mappers\UsersByLoginMapper;
use app\models\UsersModel;

/**
 * Предоставляет методы для аутентификации пользователей
 */
class UserAuthenticationHelper
{
    use ExceptionsTrait;
    
    /**
     * @var array знасения для ичистки свойств \Yii::$app->user
     */
    public static $_cleanArray = [
        'id'=>NULL,
        'login'=>NULL,
        'password'=>NULL,
        'rawPassword'=>'',
        'name'=>'',
        'surname'=>'',
        'id_emails'=>0,
        'id_phones'=>0,
        'id_address'=>0,
    ];
    
    /**
     * Инициализирует свойства класса
     * @return boolean
     */
    public static function init()
    {
        try {
            self::$_cleanArray['login'] = \Yii::$app->params['nonAuthenticatedUserLogin'];
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Заполняет объект \Yii::$app->user данными из БД
     * @param objects $userFromForm объект UserModel, плученный из формы
     * @return boolean
     */
    public static function fillFromForm(UsersModel $userFromForm)
    {
        try {
            if (empty(\Yii::$app->params['userFromFormForAuthentication'])) {
                $usersByLoginMapper = new UsersByLoginMapper([
                    'tableName'=>'users',
                    'fields'=>\Yii::$app->params['filedsFromDb'],
                    'model'=>$userFromForm
                ]);
                \Yii::$app->params['userFromFormForAuthentication'] = $usersByLoginMapper->getOneFromGroup();
            }
            
            if (is_object(\Yii::$app->params['userFromFormForAuthentication']) && \Yii::$app->params['userFromFormForAuthentication'] instanceof UsersModel) {
                if ($userFromForm->login == \Yii::$app->params['userFromFormForAuthentication']->login && password_verify($userFromForm->rawPassword, \Yii::$app->params['userFromFormForAuthentication']->password)) {
                    foreach (\Yii::$app->params['filedsToUser'] as $field) { 
                        \Yii::$app->user->$field = \Yii::$app->params['userFromFormForAuthentication']->$field;
                    }
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Заполняет объект \Yii::$app->user данными
     * @param objects $userModel объект UserModel
     * @return boolean
     */
    public static function fill(UsersModel $userModel)
    {
        try {
            foreach (\Yii::$app->params['filedsToUser'] as $field) {
                \Yii::$app->user->$field = $userModel->$field;
            }
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Обнуляет свойства объекта \Yii::$app->user данными
     * @return boolean
     */
    public static function clean()
    {
        try {
            if (!self::init()) {
                throw new ErrorException('Ошибка при вызове UserAuthenticationHelper::init');
            }
            \Yii::configure(\Yii::$app->user, self::$_cleanArray);
            return true;
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}