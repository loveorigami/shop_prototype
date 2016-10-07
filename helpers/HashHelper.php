<?php

namespace app\helpers;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;
use app\models\EmailsModel;

/**
 * Коллекция методов для создания хеша
 */
class HashHelper
{
    /**
     * Конструирует хеш с помощью функции sha1
     * @param array $inputArray массив данных для конструирования хеша
     * @return string
     */
    public static function createHash(Array $inputArray): string
    {
        try {
            if (empty($inputArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'Array $inputArray']));
            }
            
            $inputArray[] = \Yii::$app->params['hashSalt'];
            return sha1(implode('', $inputArray));
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
    
    /**
     * Конструирует хеш и пишет его во Флеш-сессию в процессе восстановления пароля
     * @param object $emailsModel
     * @return string
     */
    public static function createHashRestore(EmailsModel $emailsModel): string
    {
        try {
            $salt = random_bytes(12);
            if (!SessionHelper::writeFlash('restore.' . $emailsModel->email, $salt)) {
                throw new ErrorException(\Yii::t('base', 'Method error {placeholder}!', ['placeholder'=>'SessionHelper::writeFlash']));
            }
            return self::createHash([$emailsModel->email, $emailsModel->id, $emailsModel->users->id, $salt]);
        } catch (\Exception $e) {
            ExceptionsTrait::throwStaticException($e, __METHOD__);
        }
    }
}