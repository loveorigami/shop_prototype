<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use app\widgets\AbstractBaseWidget;
use app\forms\AbstractBaseForm;

/**
 * Формирует HTML строку с основными данными аккаунта
 */
class AdminColorsWidget extends AbstractBaseWidget
{
    /**
     * @var array ColorsModel
     */
    private $colors;
    /**
     * @var AbstractBaseForm
     */
    private $form;
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
            if (empty($this->colors)) {
                throw new ErrorException($this->emptyError('colors'));
            }
            if (empty($this->form)) {
                throw new ErrorException($this->emptyError('form'));
            }
            if (empty($this->header)) {
                throw new ErrorException($this->emptyError('header'));
            }
            if (empty($this->template)) {
                throw new ErrorException($this->emptyError('template'));
            }
            
            $renderArray = [];
            
            $renderArray['header'] = $this->header;
            
            $colorsArray = [];
            foreach ($this->colors as $color) {
                $set = [];
                $set['color'] = $color->color;
                
                $colorsForm = clone $this->form;
                $set['modelForm'] = \Yii::configure($colorsForm, ['id'=>$color->id]);
                $set['formId'] = sprintf('admin-color-delete-form-%d', $color->id);
                
                $set['ajaxValidation'] = false;
                $set['validateOnSubmit'] = false;
                $set['validateOnChange'] = false;
                $set['validateOnBlur'] = false;
                $set['validateOnType'] = false;
                
                $set['formAction'] = Url::to(['/admin/color-delete']);
                $set['button'] = \Yii::t('base', 'Delete');
                
                $colorsArray[] = $set;
            }
            
            ArrayHelper::multisort($colorsArray, 'color', SORT_ASC);
            $renderArray['colors'] = $colorsArray;
            
            return $this->render($this->template, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminColorsWidget::colors
     * @param array $colors
     */
    public function setColors(array $colors)
    {
        try {
            $this->colors = $colors;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminColorsWidget::form
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
     * Присваивает значение AdminColorsWidget::header
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
     * Присваивает значение AdminColorsWidget::template
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
