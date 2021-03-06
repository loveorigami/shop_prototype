<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;
use app\models\CurrencyInterface;
use app\helpers\ImgHelper;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AccountCurrentOrdersWidget extends AbstractBaseWidget
{
    /**
     * @var array PurchasesModel
     */
    private $purchases;
    /**
     * @var CurrencyInterface
     */
    private $currency;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку с данными
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->currency)) {
                throw new ErrorException($this->emptyError('currency'));
            }
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            if (!empty($this->purchases)) {
                $purchases = array_filter($this->purchases, function($item) {
                    return ((int) $item->canceled === 0 && (int) $item->shipped === 0) ? true : false;
                });
                
                if (!empty($purchases)) {
                    $renderArray['header'] = $this->header;
                    
                    ArrayHelper::multisort($purchases, 'received_date', SORT_DESC, SORT_REGULAR);
                    
                    foreach ($purchases as $purchase) {
                        $set = [];
                        $set['id'] = $purchase->id;
                        $set['date'] = \Yii::$app->formatter->asDate($purchase->received_date);
                        $set['link'] = Url::to(['/product-detail/index', 'seocode'=>$purchase->product->seocode], true);
                        $set['linkText'] = $purchase->product->name;
                        $set['short_description'] = $purchase->product->short_description;
                        $set['quantity'] = $purchase->quantity;
                        $set['price'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal($purchase->price * $this->currency->exchangeRate(), 2), $this->currency->code());
                        $set['totalPrice'] = sprintf('%s %s', \Yii::$app->formatter->asDecimal(($purchase->price * $purchase->quantity) * $this->currency->exchangeRate(), 2), $this->currency->code());
                        $set['color'] = $purchase->color->color;
                        $set['size'] = $purchase->size->size;
                        if (!empty($purchase->product->images)) {
                            $set['image'] = ImgHelper::randThumbn($purchase->product->images);
                        }
                        
                        if ((bool) $purchase->processed === true) {
                            $set['status'] = \Yii::t('base', 'Processed');
                        } else {
                            $set['status'] = \Yii::t('base', 'Received');
                        }
                        
                        $renderArray['purchases'][] = $set;
                    }
                    
                    $renderArray['dateHeader'] = \Yii::t('base', 'Order date');
                    $renderArray['idHeader'] = \Yii::t('base', 'Order number');
                    $renderArray['quantityHeader'] = \Yii::t('base', 'Quantity');
                    $renderArray['priceHeader'] = \Yii::t('base', 'Price');
                    $renderArray['totalPriceHeader'] = \Yii::t('base', 'Total price');
                    $renderArray['colorHeader'] = \Yii::t('base', 'Color');
                    $renderArray['sizeHeader'] = \Yii::t('base', 'Size');
                    $renderArray['statusHeader'] = \Yii::t('base', 'Status');
                }
            }
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array PurchasesModel свойству AccountCurrentOrdersWidget::purchases
     * @param array $purchases
     */
    public function setPurchases(array $purchases)
    {
        try {
            $this->purchases = $purchases;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает CurrencyInterface свойству AccountCurrentOrdersWidget::currency
     * @param CurrencyInterface $currency
     */
    public function setCurrency(CurrencyInterface $currency)
    {
        try {
            $this->currency = $currency;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает заголовок свойству AccountCurrentOrdersWidget::header
     * @param string $header
     */
    public function setHeader(string $header)
    {
        try {
            $this->header = $header;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает имя шаблона свойству AccountCurrentOrdersWidget::template
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        try {
            $this->template = $template;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
