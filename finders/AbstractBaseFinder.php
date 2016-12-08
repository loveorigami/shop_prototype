<?php

namespace app\finders;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\finders\{BaseTrait,
    FinderInterface};
use app\collections\CollectionInterface;

/**
 * Возвращает коллекцию товаров для каталога
 */
abstract class AbstractBaseFinder extends Model implements FinderInterface
{
    use ExceptionsTrait, BaseTrait;
    
    /**
     * @var object CollectionInterface
     */
    protected $collection;
    
    /**
     * Присваивает CollectionInterface свойству static::collection
     * @param object $collection CollectionInterface
     */
    public function setCollection(CollectionInterface $collection)
    {
        try {
            $this->collection = $collection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}