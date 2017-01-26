<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Html,
    Url};
use app\widgets\AbstractBaseWidget;

/**
 * Выводит информацию о популярных товарах
 */
class PopularGoodsWidget extends AbstractBaseWidget
{
    /**
     * @var array ProductsModel
     */
    private $goods;
    /**
     * @var string заголовок
     */
    public $header;
    /**
     * @var string имя шаблона
     */
    public $view;
    
    public function run()
    {
        try {
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->view)) {
                throw new ErrorException($this->emptyError('view'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            if (!empty($this->goods)) {
                ArrayHelper::multisort($this->goods, 'views', SORT_DESC, SORT_NUMERIC);
                
                foreach ($this->goods as $product) {
                    $set = [];
                    $set['views'] = $product->views;
                    $set['link'] = Url::to(['/product-detail/index', 'seocode'=>$product->seocode], true);
                    $set['linkText'] = Html::encode($product->name);
                    $set['short_description'] = Html::encode($product->short_description);
                    if (!empty($product->images)) {
                        $imagesArray = glob(\Yii::getAlias('@imagesroot/' . $product->images) . '/thumbn_*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                        if (!empty($imagesArray)) {
                            $set['image'] = Html::img(\Yii::getAlias('@imagesweb/' . $product->images . '/') . basename($imagesArray[random_int(0, count($imagesArray) - 1)]), ['height'=>200]);
                        }
                    }
                    $renderArray['goods'][] = $set;
                }
                
                $renderArray['viewsHeader'] = \Yii::t('base', 'Views');
            } else {
                $renderArray['goodsEmpty'] = \Yii::t('base', 'No popular items');
            }
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает array ProductsModel свойству PopularGoodsWidget::goods
     * @param array $goods
     */
    public function setGoods(array $goods)
    {
        try {
            $this->goods = $goods;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}