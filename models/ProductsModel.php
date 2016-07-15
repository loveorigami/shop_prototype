<?php

namespace app\models;

use app\models\AbstractBaseModel;
use yii\base\ErrorException;
use app\helpers\MappersHelper;

/**
 * Представляет данные таблицы products
 */
class ProductsModel extends AbstractBaseModel
{
    /**
     * Сценарий загрузки данных из БД в рамках списка продуктов
    */
    const GET_LIST_FROM_DB = 'getListFromBd';
    /**
     * Сценарий загрузки данных из формы, добавляющей продукт в корзину
    */
    const GET_FROM_FORM_TO_CART = 'getFromFormToCart';
    /**
     * Сценарий загрузки данных из формы, удаляющей продукт из корзины
    */
    const GET_FROM_FORM_FOR_REMOVE = 'getFromFormForRemove';
    /**
     * Сценарий загрузки данных из формы, инициирующей очищающение корзины
    */
    const GET_FROM_FORM_FOR_CLEAR_CART = 'getFromFormForClearCart';
    /**
     * Сценарий загрузки данных из формы добавления продукта
    */
    const GET_FROM_ADD_PRODUCT_FORM = 'getFromAddProductForm';
    
    public $id = '';
    public $date = '';
    public $code = '';
    public $name = '';
    public $description = '';
    public $price = '';
    public $images = array();
    public $id_categories = '';
    public $id_subcategory = '';
    
    /**
     * Свойства получаемые при выборке связанных и похожих продуктов, например, для построения ссылок
     */
    public $categories = '';
    public $subcategory = '';
    
    /**
     * @var string хэш сумма для продукта, мспользуется для идентификации в массиве продуктов класса корзины
     */
    public $hash = '';
    /**
     * @var array массив свойств, на основании которых создается хэш
     */
    public $safeProperties = ['id', 'code', 'colorToCart', 'sizeToCart'];
    
    /**
     * Свойства получаемые из формы добавления в корзину
     */
    public $colorToCart = '';
    public $sizeToCart = '';
    public $quantity = '';
    
    private $_colors = NULL;
    private $_sizes = NULL;
    private $_similar = NULL;
    private $_related = NULL;
    private $_comments = NULL;
    
    public function scenarios()
    {
        return [
            self::GET_LIST_FROM_DB=>['id', 'date', 'code', 'name', 'description', 'price', 'images', 'categories', 'subcategory'],
            self::GET_FROM_FORM_TO_CART=>['id', 'code', 'name', 'description', 'price', 'colorToCart', 'sizeToCart', 'quantity', 'categories', 'subcategory', 'hash'],
            self::GET_FROM_FORM_FOR_REMOVE=>['id', 'hash'],
            self::GET_FROM_FORM_FOR_CLEAR_CART=>['id', 'categories', 'subcategory'],
            self::GET_FROM_ADD_PRODUCT_FORM=>['code', 'name', 'description', 'price', 'images', 'id_categories', 'id_subcategory'],
        ];
    }
    
    public function rules()
    {
        return [
            [['code', 'name', 'description', 'price', 'images', 'id_categories', 'id_subcategory'], 'required', 'on'=>self::GET_FROM_ADD_PRODUCT_FORM],
        ];
    }
    
    /**
     * Возвращает по запросу массив объектов colors, связанных с текущим объектом
     * @return array
     */
    public function getColors()
    {
        try {
            if (is_null($this->_colors)) {
                if (!empty($this->id)) {
                    $this->_colors = MappersHelper::getColorsForProductList($this);
                }
            }
            return $this->_colors;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает по запросу массив объектов sizes, связанных с текущим объектом
     * @return array
     */
    public function getSizes()
    {
        try {
            if (is_null($this->_sizes)) {
                if (!empty($this->id)) {
                    $this->_sizes = MappersHelper::getSizesForProductList($this);
                }
            }
            return $this->_sizes;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает по запросу массив объектов similar products, связанных с текущим объектом
     * @return array
     */
    public function getSimilar()
    {
        try {
            if (is_null($this->_similar)) {
                if (!empty($this->id)) {
                    $this->_similar = MappersHelper::getSimilarProductsList($this);
                }
            }
            return $this->_similar;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Возвращает по запросу массив объектов related products, связанных с текущим объектом
     * @return array
     */
    public function getRelated()
    {
        try {
            if (is_null($this->_related)) {
                if (!empty($this->id)) {
                    $this->_related = MappersHelper::getRelatedProductsList($this);
                }
            }
            return $this->_related;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        
    }
    
    /**
     * Возвращает по запросу массив объектов comments, связанных с текущим объектом
     * @return array
     */
    public function getComments()
    {
        try {
            if (is_null($this->_comments)) {
                if (!empty($this->id)) {
                    $this->_comments = MappersHelper::getCommentsForProductList($this);
                }
            }
            return $this->_comments;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Создает md5 hash из значений свойств, однозначно идентифицируя объект
     * @return boolean
     */
    public function getHash()
    {
        try {
            $stringToHash = '';
            foreach ($this->safeProperties as $property) {
                $stringToHash .= $this->$property;
            }
            if (!$this->hash = md5($stringToHash)) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
