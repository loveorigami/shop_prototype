<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    AbstractSendFormAsset,
    MainAsset};

/**
 * Задает основной пакет ресурсов
 */
class PasswordRecoveryAsset extends AbstractAsset
{
    /**
     * @var array массив js параметров
     */
    public $jsOptions = [
        'defer'=>true,
    ];
    /**
     * @var array массив, перечисляющий JavaScript файлы, 
     * содержащиеся в данном комплекте
     */
    public $js = [
        'js/sendPasswordRecovery.js',
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        MainAsset::class,
        AbstractSendFormAsset::class,
    ];
}
