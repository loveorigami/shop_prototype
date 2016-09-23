<?php

namespace app\console;

use yii\console\Controller;
use yii\helpers\Console;
use app\exceptions\ExceptionsTrait;
use app\models\ProductsModel;
use app\helpers\TransliterationHelper;

/**
 * Записывает и удаляет значение поля seocode таблицы products
 */
class ProductsSeocodeController extends Controller
{
    use ExceptionsTrait;
    
    /**
     * Заполняет значение поля seocode для всех записей
     */
    public function actionSet()
    {
        try {
            $productsArray = ProductsModel::find()->all();
            $this->stdout(\Yii::t('base/console', "Fount {count} objects...\n", ['count'=>count($productsArray)]));
            $this->stdout(\Yii::t('base/console', "Begin update...\n"));
            foreach ($productsArray as $product) {
                $product->scenario = ProductsModel::GET_FROM_DB;
                $seocode = TransliterationHelper::getTransliterationSeparate($product->name);
                if (ProductsModel::find()->where(['seocode'=>$seocode])->exists()) {
                    $seocode .= '-' . $product->id;
                }
                $product->seocode = $seocode;
                $this->stdout('id: ' . $product->id . ', seocode: ' . $seocode . "\n");
                $product->update();
            }
            $this->stdout(\Yii::t('base/console', "Update successful!\n"));
            return parent::EXIT_CODE_NORMAL;
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
            $this->stderr(\Y::t('base/console', "Update error!\n"), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
    
    /**
     * Удаляет значение поля seocode для всех записей в БД
     */
    public function actionUnset()
    {
        try {
            $this->stdout(\Yii::t('base/console', "Begin delete...\n"));
            \Yii::$app->db->createCommand()->update('products', ['seocode'=>''])->execute();
            $this->stdout(\Yii::t('base/console', "Delete successful!\n"));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
            $this->stderr(\Y::t('base/console', "Delete error!\n"), Console::FG_RED);
            return parent::EXIT_CODE_ERROR;
        }
    }
}