<?php

namespace app\repository;

use yii\base\ErrorException;
use app\repository\GetGroupRepositoryInterface;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;

class GetSimilarProductsRepository implements GetGroupRepositoryInterface
{
    use ExceptionsTrait;
    
    private $items = [];
    
    public function getGroup(ProductsModel $model): array
    {
        try {
            if (empty($this->items)) {
                $query = ProductsModel::find();
                $query->distinct();
                $query->where(['!=', '[[id]]', $model->id]);
                $query->andWhere(['[[id_category]]'=>$model->category->id]);
                $query->andWhere(['[[id_subcategory]]'=>$model->subcategory->id]);
                $query->innerJoin('{{products_colors}}', '[[products_colors.id_product]]=[[products.id]]');
                $query->andWhere(['[[products_colors.id_color]]'=>ArrayHelper::getColumn($model->colors, 'id')]);
                $query->innerJoin('{{products_sizes}}', '[[products_sizes.id_product]]=[[products.id]]');
                $query->andWhere(['[[products_sizes.id_size]]'=>ArrayHelper::getColumn($model->sizes, 'id')]);
                $query->limit(\Yii::$app->params['similarLimit']);
                $array = $query->all();
                if (!empty($array)) {
                    $this->items = $array;
                }
            }
            
            return $this->items;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
