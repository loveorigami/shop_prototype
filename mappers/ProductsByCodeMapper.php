<?php

namespace app\mappers;

use app\mappers\AbstractGetMapper;
use yii\base\ErrorException;
use app\models\ProductsModel;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class ProductsByCodeMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\ProductsByCodeQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\ProductsObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof ProductsModel) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            
            if (empty($this->params)) {
                $this->params = [':code'=>$this->model->code];
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
