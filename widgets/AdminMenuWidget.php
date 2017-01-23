<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\widgets\Menu;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует меню раздела настроек аккаунта
 */
class AdminMenuWidget extends Menu
{
    use ExceptionsTrait;
    
    /**
     * @var array HTML атрибуты, которые будут применены к тегу-контейнеру меню (ul по-умолчанию)
     */
    public $options = ['class'=>'admin-menu'];
    
    public function init()
    {
        try {
            parent::init();
            
            $this->setItems();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Формирует массив ссылок для создания меню
     */
    private function setItems()
    {
        try {
            $this->items = [
                [
                    'label'=>\Yii::t('base', 'Basic data'),
                    'url'=>['/admin/index']
                ],
                [
                    'label'=>\Yii::t('base', 'Orders'),
                    'url'=>['/admin/index']
                ],
                [
                    'label'=>\Yii::t('base', 'Products'),
                    'url'=>['/admin/index']
                ],
                [
                    'label'=>\Yii::t('base', 'Users'),
                    'url'=>['/admin/index']
                ],
                [
                    'label'=>\Yii::t('base', 'Exit'),
                    'url'=>['/']
                ],
            ];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
