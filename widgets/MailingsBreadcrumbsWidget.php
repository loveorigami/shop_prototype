<?php

namespace app\widgets;

use yii\base\ErrorException;
use app\widgets\BreadcrumbsWidget;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует breadcrumbs для страницы аутентификации
 */
class MailingsBreadcrumbsWidget extends BreadcrumbsWidget
{
    use ExceptionsTrait;
    
    public function init()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['label'=>\Yii::t('base', 'Mailings')];
            
            parent::init();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
