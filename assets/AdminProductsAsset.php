<?php

namespace app\assets;

use app\assets\{AbstractAsset,
    AbstractSendFormAsset,
    MainAsset};

/**
 * Задает основной пакет ресурсов
 */
class AdminProductsAsset extends AbstractAsset
{
    /**
     * @var array массив, перечисляющий JavaScript файлы, 
     * содержащиеся в данном комплекте
     */
    public $js = [
        'js/sendGetSubcategory.js',
        'js/sendAdminProductsGetCsv.js',
    ];
    /**
     * @var array зависимости пакета
     */
    public $depends = [
        MainAsset::class,
        AbstractSendFormAsset::class,
    ];
}