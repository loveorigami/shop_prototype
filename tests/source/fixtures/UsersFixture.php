<?php

namespace app\tests\source\fixtures;

use app\tests\source\fixtures\AbstractFixture;

/**
 * Фикстура таблицы users
 */
class UsersFixture extends AbstractFixture
{
    /**
     * @var string имя класса ActiveRecord, представляющего таблицу users
     */
    public $modelClass = 'app\models\UsersModel';
    /**
     * @var array массив имен классов-фикстур, представляющих данные, от которых зависит users
     */
    public $depends = [
        'app\tests\source\fixtures\EmailsFixture',
    ];
}
