<?php

namespace app\collections;

use yii\base\ErrorException;
use app\exceptions\ExceptionsTrait;
use app\collections\{AbstractIterator,
    BaseTrait,
    SessionCollectionInterface};

/**
 * Реализует интерфейс доступа к коллекции объектов, 
 * полученной из сессионного хранилища
 */
class BaseSessionCollection extends AbstractIterator implements SessionCollectionInterface
{
    use ExceptionsTrait, BaseTrait;
    
    /**
     * Получает объекты из сессии и добавляет их в коллекцию
     * @return $this
     */
    public function getModels()
    {
        return null;
    }
    
    /**
     * Возвращает 1 объект из коллекции
     */
    public function getModel()
    {
        return null;
    }
    
    /**
     * Возвращает 1 массив из коллекции
     */
    public function getArray()
    {
        try {
            if ($this->isEmpty()) {
                throw new ErrorException($this->emptyError('items'));
            }
            if ($this->isArrays() === false) {
                throw new ErrorException($this->invalidError('items'));
            }
            
            return $this->items[0];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}