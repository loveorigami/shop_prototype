<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Widget};
use app\exceptions\ExceptionsTrait;
use app\repository\{GetGroupRepositoryInterface,
    GetOneRepositoryInterface};
use app\helpers\HashHelper;
use app\models\{CurrencyModel,
    PurchasesCompositInterface};

/**
 * Формирует HTML строку с информацией о текущем статусе корзины заказов
 */
class CartWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object GetGroupRepositoryInterface для поиска данных по запросу
     */
    private $repository;
    /**
     * @var object GetOneRepositoryInterface для поиска данных по запросу
     */
    private $currency;
    /**
     * @var string имя шаблона
     */
    public $view;
    /**
     * @var int общее количество товаров в корзине
     */
    private $goods = 0;
    /**
     * @var int общая стоимость товаров в корзине
     */
    private $cost = 0;
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->repository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('repository'));
            }
            if (empty($this->currency)) {
                throw new ErrorException(ExceptionsTrait::emptyError('currency'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('view'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует HTML строку с информацией о текущем статусе корзины заказов
     * @return string
     */
    public function run()
    {
        try {
            $key = HashHelper::createHash([\Yii::$app->params['cartKey'], \Yii::$app->user->id ?? '']);
            $purchases = $this->repository->getGroup($key);
            
            if (!empty($purchases) && $purchases instanceof PurchasesCompositInterface) {
                $this->goods = $purchases->quantity;
                $this->cost = $purchases->price;
            }
            
            $currency = $this->currency->getOne(\Yii::$app->params['currencyKey']);
            if ($currency instanceof CurrencyModel && !empty($currency->exchange_rate) && !empty($currency->code)) {
                $this->cost = \Yii::$app->formatter->asDecimal($this->cost * $currency->exchange_rate, 2) . ' ' . $currency->code;
            }
            
            return $this->render($this->view, ['goods'=>$this->goods, 'cost'=>$this->cost]);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает GetGroupRepositoryInterface свойству CartWidget::repository
     * @param object $repository GetGroupRepositoryInterface
     */
    public function setRepository(GetGroupRepositoryInterface $repository)
    {
        try {
            $this->repository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает GetOneRepositoryInterface свойству CartWidget::currency
     * @param object $currency GetOneRepositoryInterface
     */
    public function setCurrency (GetOneRepositoryInterface $currency)
    {
        try {
            $this->currency = $currency;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
