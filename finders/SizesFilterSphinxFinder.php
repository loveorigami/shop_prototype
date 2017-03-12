<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\SizesModel;
use app\finders\AbstractBaseFinder;

/**
 * Возвращает коллекцию цветов из СУБД
 */
class SizesFilterSphinxFinder extends AbstractBaseFinder
{
    /**
     * @var array массив ID товаров, найденный sphinx в ответ на запрос
     */
    private $sphinx;
    /**
     * @var массив загруженных SizesModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find()
    {
        try {
            if (empty($this->sphinx)) {
                throw new ErrorException($this->emptyError('sphinx'));
            }
            
            if (empty($this->storage)) {
                $query = SizesModel::find();
                $query->select(['[[sizes.id]]', '[[sizes.size]]']);
                $query->distinct();
                $query->innerJoin('{{products_sizes}}', '[[sizes.id]]=[[products_sizes.id_size]]');
                $query->innerJoin('{{products}}', '[[products_sizes.id_product]]=[[products.id]]');
                $query->where(['[[products.active]]'=>true]);
            
                $query->andWhere(['[[products.id]]'=>$this->sphinx]);
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array SizesFilterSphinxFinder::sphinx
     * @param array $sphinx
     */
    public function setSphinx(array $sphinx)
    {
        try {
            $this->sphinx = $sphinx;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
