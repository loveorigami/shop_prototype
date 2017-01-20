<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;

/**
 * Представляет данные формы фильтров для каталога товаров
 */
class PurchaseForm extends AbstractBaseForm
{
    /**
     * Сценарий сохранения товара в корзину
     */
    const SAVE = 'save';
    /**
     * Сценарий обновления товара в корзине
     */
    const UPDATE = 'update';
    /**
     * Сценарий удаления товара из корзины
     */
    const DELETE = 'delete';
    /**
     * Сценарий отмены заказа
     */
    const CANCEL = 'cancel';
    
    /**
     * @var int количество единиц товара
     */
    public $quantity;
    /**
     * @var int Iцвет товара
     */
    public $id_color;
    /**
     * @var int размер товара
     */
    public $id_size;
    /**
     * @var int ID товара
     */
    public $id_product;
    /**
     * @var float стоимость товара
     */
    public $price;
    /**
     * @var bool отмена заказа
     */
    public $cancel;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['quantity', 'id_color', 'id_size', 'id_product', 'price'],
            self::UPDATE=>['quantity', 'id_color', 'id_size', 'id_product'],
            self::DELETE=>['id_product'],
            self::CANCEL=>['cancel']
        ];
    }
    
    public function rules()
    {
        return [
            [['quantity', 'id_color', 'id_size', 'id_product', 'price'], 'required', 'on'=>self::SAVE],
            [['quantity', 'id_color', 'id_size', 'id_product'], 'required', 'on'=>self::UPDATE],
            [['id_product'], 'required', 'on'=>self::DELETE],
            [['cancel'], 'default', 'value'=>0, 'on'=>self::CANCEL]
        ];
    }
}
