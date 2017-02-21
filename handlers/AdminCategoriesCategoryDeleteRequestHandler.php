<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\{CategoriesForm,
    SubcategoryForm};
use app\finders\{CategoriesFinder,
    CategoryIdFinder};
use app\models\CategoriesModel;
use app\removers\CategoriesModelRemover;
use app\widgets\AdminCategoriesWidget;

/**
 * Обрабатывает запрос на обновление данных товара
 */
class AdminCategoriesCategoryDeleteRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * Обновляет данные товара
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new CategoriesForm(['scenario'=>CategoriesForm::DELETE]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $finder = \Yii::$app->registry->get(CategoryIdFinder::class, [
                            'id'=>$form->id
                        ]);
                        $categoriesModel = $finder->find();
                        
                        $categoriesModel->scenario = CategoriesModel::DELETE;
                        if ($categoriesModel->validate() === false) {
                            throw new ErrorException($this->modelError($categoriesModel->errors));
                        }
                        
                        $remover = new CategoriesModelRemover([
                            'model'=>$categoriesModel
                        ]);
                        $remover->remove();
                        
                        $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                        $categoriesModelArray = $finder->find();
                        
                        $categoriesForm = new CategoriesForm(['scenario'=>CategoriesForm::DELETE]);
                        $subcategoryForm = new SubcategoryForm(['scenario'=>SubcategoryForm::DELETE]);
                        
                        $adminCategoriesWidgetConfig = $this->adminCategoriesWidgetConfig($categoriesModelArray, $categoriesForm, $subcategoryForm);
                        $response = AdminCategoriesWidget::widget($adminCategoriesWidgetConfig);
                        
                        $transaction->commit();
                        
                        return $response;
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}