<?php

namespace app\finders;

use yii\base\ErrorException;
use app\models\BrandsModel;
use app\finders\{AbstractBaseFinder,
    BrandsFilterFindersTrait};

/**
 * Возвращает коллекцию цветов из СУБД
 */
class BrandsFilterFinder extends AbstractBaseFinder
{
    use BrandsFilterFindersTrait;
    
    /**
     * @var string GET параметр, определяющий текущую категорию каталога товаров
     */
    private $category;
    /**
     * @var string GET параметр, определяющий текущую подкатегорию каталога товаров
     */
    private $subcategory;
    /**
     * @var массив загруженных BrandsModel
     */
    private $storage = null;
    
    /**
     * Возвращает данные из СУБД
     * @return CollectionInterface
     */
    public function find()
    {
        try {
            if (empty($this->storage)) {
                $query = $this->createQuery();
            
                if (!empty($this->category)) {
                    $query->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
                    $query->andWhere(['[[categories.seocode]]'=>$this->category]);
                    if (!empty($this->subcategory)) {
                        $query->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
                        $query->andWhere(['[[subcategory.seocode]]'=>$this->subcategory]);
                    }
                }
                
                $this->storage = $query->all();
            }
            
            return $this->storage;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает категорию свойству BrandsFilterFinder::category
     * @param string $category
     */
    public function setCategory(string $category)
    {
        try {
            $this->category = $category;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает подкатегорию свойству BrandsFilterFinder::subcategory
     * @param string $subcategory
     */
    public function setSubcategory(string $subcategory)
    {
        try {
            $this->subcategory = $subcategory;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
