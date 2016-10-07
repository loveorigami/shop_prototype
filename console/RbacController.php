<?php

namespace app\console;

use yii\console\Controller;
use yii\helpers\Console;
use app\exceptions\ExceptionsTrait;
use app\rbac\rules\UserDataEditingRule;

/**
 * Инициирует создание RBAC данных авторизации
 */
class RbacController extends Controller
{
    use ExceptionsTrait;
    
    /**
     * Настраивает права доступа
     */
    public function actionSet()
    {
        try {
            $auth = \Yii::$app->authManager;
            
            $this->stdout(\Yii::t('base/console', "Create RBAC authorization data...\n"));
            
            # Права на просмотр и редактирование данных зарегистрированного пользователя
            $userDataEditingRule = new UserDataEditingRule();
            $auth->add($userDataEditingRule);
            $userDataEditingPermission = $auth->createPermission('userDataEditing');
            $userDataEditingPermission->description = \Yii::t('base', 'User data editing');
            $userDataEditingPermission->ruleName = $userDataEditingRule->name;
            $auth->add($userDataEditingPermission);
            
            # Зарегистрированный пользователь
            $user = $auth->createRole('user');
            $auth->add($user);
            $auth->addChild($user, $userDataEditingPermission);
            
            $this->stdout(\Yii::t('base/console', "Create an authorization RBAC successfully completed!\n"));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->stderr(\Y::t('base/console', "Error creating RBAC!\n"), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
    
    /**
     * Удаляет файлы с правами доступа
     */
    public function actionUnset()
    {
        try {
            $this->stdout(\Yii::t('base/console', "Removing RBAC authorization data...\n"));
            
            $filePaths = glob('/var/www/html/shop/rbac/*.php');
            if (!empty($filePaths)) {
                foreach ($filePaths as $file) {
                    unlink($file);
                }
            }
            
            $this->stdout(\Yii::t('base/console', "Removing an authorization RBAC successfully completed!\n"));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->stderr(\Y::t('base/console', "Error removing RBAC!\n"), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
}