<?php

namespace app\repository;

use yii\base\{ErrorException,
    Object};
use app\repository\GetGroupRepositoryInterface;
use app\exceptions\ExceptionsTrait;
use app\models\{CategoriesCompositInterface,
    CategoriesModel};

class CategoriesRepository extends Object implements GetGroupRepositoryInterface
{
    use ExceptionsTrait;
    
    /**
     * @var object CategoriesCompositInterface
     */
    private $items;
    
    /**
     * Возвращает CategoriesCompositInterface, содержащий коллекцию CategoriesModel
     * @return CategoriesCompositInterface или null
     */
    public function getGroup($data=null)
    {
        try {
            if (empty($this->items)) {
                throw new ErrorException(ExceptionsTrait::emptyError('items'));
            }
            
            if ($this->items->isEmpty()) {
                $data = CategoriesModel::find()->with('subcategory')->all();
                if (!empty($data)) {
                    foreach ($data as $object) {
                        $this->items->add($object);
                    }
                }
            }
            
            return !empty($this->items) ? $this->items : null;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CategoriesCompositInterface свойству CategoriesRepository::items
     * @param object $composit CategoriesCompositInterface
     */
    public function setItems(CategoriesCompositInterface $composit)
    {
        try {
            $this->items = $composit;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
