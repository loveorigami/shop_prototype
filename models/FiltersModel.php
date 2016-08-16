<?php

namespace app\models;

use app\models\AbstractBaseModel;

/**
 * Представляет данные, полученные из формы фильтров
 */
class FiltersModel extends AbstractBaseModel
{
    public $colors = array();
    public $sizes = array();
    public $brands = array();
    
    public $sortingField;
    public $sortingType;
    
    /**
     * Свойства содержат данные для редиректа после обработки запроса
     */
    public $categories = '';
    public $subcategory = '';
    public $search = '';
    
    //public $active = true;
    /**
     * Свойства для фильтрации активных/неактивных товаров в административном разделе
     */
    public $getActive = true; #!!!TEST Геттер, если оба сняты, вернуть оба true для сессии
    public $getNotActive = true; #!!!TEST
    
    public function rules()
    {
        return [
            [['colors', 'sizes', 'brands', 'sortingField', 'sortingType', 'categories', 'subcategory', 'search', 'getActive', 'getNotActive'], 'safe'],
        ];
    }
    
    /**
     * Обнуляет значение всех свойств, очищая фильтры
     * @return boolean
     */
    public function clean()
    {
        try {
            $this->colors = array();
            $this->sizes = array();
            $this->brands = array();
            $this->sortingField = '';
            $this->sortingType = '';
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обнуляет значение свойств, необходимых для построения URL
     * @return boolean
     */
    public function cleanOther()
    {
        try {
            $this->categories = '';
            $this->subcategory = '';
            $this->search = '';
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Обнуляет значение свойств, очищая фильтры Admin
     * @return boolean
     */
    public function cleanAdmin()
    {
        try {
            $this->categories = '';
            $this->subcategory = '';
            $this->getActive = true; #!!!TEST
            $this->getNotActive = true; #!!!TEST
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
