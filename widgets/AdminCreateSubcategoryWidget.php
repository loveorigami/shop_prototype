<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует HTML строку с формой регистрации
 */
class AdminCreateSubcategoryWidget extends AbstractBaseWidget
{
    /**
     * @var AbstractBaseForm
     */
    private $form;
    /**
     * @var array CategoriesModel
     */
    private $categories;
    /**
     * @var string заголовок
     */
    private $header;
    /**
     * @var string имя шаблона
     */
    private $template;
    
    /**
     * Конструирует HTML строку с формой регистрации
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->categories)) {
                throw new ErrorException($this->emptyError('categories'));
            }
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            $renderArray['formModel'] = $this->form;
            $renderArray['formId'] = 'subcategory-create-form';
            
            $renderArray['ajaxValidation'] = false;
            $renderArray['validateOnSubmit'] = true;
            $renderArray['validateOnChange'] = false;
            $renderArray['validateOnBlur'] = false;
            $renderArray['validateOnType'] = false;
            
            $categoriesModelArray = ArrayHelper::map($this->categories, 'id', 'name');
            asort($categoriesModelArray, SORT_STRING);
            $renderArray['categories'] = ArrayHelper::merge([\Yii::$app->params['formFiller']], $categoriesModelArray);
            
            $renderArray['formAction'] = Url::to(['/admin/categories-subcategory-create']);
            $renderArray['button'] = \Yii::t('base', 'Create');
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminCreateSubcategoryWidget::form
     * @param AbstractBaseForm $form
     */
    public function setForm(AbstractBaseForm $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminCreateSubcategoryWidget::categories
     * @param array $categories
     */
    public function setCategories(array $categories)
    {
        try {
            $this->categories = $categories;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminCreateSubcategoryWidget::header
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
     * Присваивает значение AdminCreateSubcategoryWidget::template
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
