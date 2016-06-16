<?php

namespace app\mappers;

use app\mappers\AbstractGetOneByMapper;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class UsersByLoginMapper extends AbstractGetOneByMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\UsersByLoginQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\UsersOneObjectsFactory';
    
    public function init()
    {
        parent::init();
        
        $this->params = [':login'=>$this->model->login];
    }
}
