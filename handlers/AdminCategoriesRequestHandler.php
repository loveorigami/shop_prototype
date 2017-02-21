<?php

namespace app\handlers;

use yii\base\ErrorException;
use app\handlers\AbstractBaseHandler;
use app\finders\CategoriesFinder;
use app\forms\{AbstractBaseForm,
    CategoriesForm,
    SubcategoryForm};

/**
 * Обрабатывает запрос на получение данных 
 * с перечнем заказов
 */
class AdminCategoriesRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param yii\web\Request $request
     */
    public function handle($request)
    {
        try {
            if (empty($this->dataArray)) {
                $finder = \Yii::$app->registry->get(CategoriesFinder::class);
                $categoriesModelArray = $finder->find();
                
                $categoriesFormDelete = new CategoriesForm(['scenario'=>CategoriesForm::DELETE]);
                $subcategoryForm = new SubcategoryForm(['scenario'=>SubcategoryForm::DELETE]);
                $categoriesFormCreate = new CategoriesForm(['scenario'=>CategoriesForm::CREATE]);
                
                $dataArray = [];
                
                $dataArray['adminCategoriesWidgetConfig'] = $this->adminCategoriesWidgetConfig($categoriesModelArray, $categoriesFormDelete, $subcategoryForm);
                $dataArray['adminCreateCategoryWidgetConfig'] = $this->adminCreateCategoryWidgetConfig($categoriesFormCreate);
                
                $this->dataArray = $dataArray;
            }
            
            return $this->dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCreateCategoryWidget
     * @param AbstractBaseForm $categoriesForm
     */
    private function adminCreateCategoryWidgetConfig(AbstractBaseForm $categoriesForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['form'] = $categoriesForm;
            $dataArray['header'] = \Yii::t('base', 'Create category');
            $dataArray['template'] = 'admin-create-category.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
