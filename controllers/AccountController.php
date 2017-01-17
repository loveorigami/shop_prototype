<?php

namespace app\controllers;

use yii\web\Controller;
use app\actions\GetAction;
use app\services\AccountIndexService;

/**
 * Обрабатывает запросы к настройкам аккаунта
 */
class AccountController extends Controller
{
    public function actions()
    {
        return [
            'index'=>[
                'class'=>GetAction::class,
                'service'=>new AccountIndexService(),
                'view'=>'index.twig',
            ],
        ];
    }
}
