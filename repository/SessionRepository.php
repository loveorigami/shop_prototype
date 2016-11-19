<?php

namespace app\repository;

use yii\base\ErrorException;
use app\repository\{AbstractBaseRepository,
    RepositoryInterface};
use app\exceptions\ExceptionsTrait;
use app\models\CollectionInterface;
use app\helpers\SessionHelper;

class SessionRepository extends AbstractBaseRepository implements RepositoryInterface
{
    /**
     * @var string имя класса ActiveRecord/Model
     */
    public $class;
    /**
     * @var object CollectionInterface
     */
    private $items;
    /**
     * @var object Model
     */
    private $item;
    
    /**
     * Возвращает объект yii\base\Model
     * @return object/null
     */
    public function getOne($key)
    {
        try {
            if (empty($this->item)) {
                $data = SessionHelper::read($key);
                if (!empty($data)) {
                    $this->item = \Yii::createObject(array_merge(['class'=>$this->class], $data));
                }
            }
            
            return !empty($this->item) ? $this->item : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает объект CollectionInterface
     * @return CollectionInterface/null
     */
    public function getGroup($key)
    {
        try {
            if (empty($this->items)) {
                throw new ErrorException(ExceptionsTrait::emptyError('items'));
            }
            
            if ($this->items->isEmpty()) {
                $data = SessionHelper::read($key);
                if (!empty($data)) {
                    foreach ($data as $item) {
                        $this->items->add(\Yii::createObject(array_merge(['class'=>$this->class], $item)));
                    }
                }
            }
            
            return !empty($data) ? $this->items : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CollectionInterface свойству DbRepository::items
     * @param object $composit CollectionInterface
     */
    public function setItems(CollectionInterface $composit)
    {
        try {
            $this->items = $composit;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
