<?php

namespace app\handlers;

use yii\base\{ErrorException,
    Model};
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\AbstractBaseHandler;
use app\forms\{AbstractBaseForm,
    CommentForm};
use app\widgets\AdminCommentFormWidget;
use app\finders\CommentIdFinder;

/**
 * Обрабатывает запрос на получение данных 
 * с формой редактирования деталей товара
 */
class AdminCommentFormRequestHandler extends AbstractBaseHandler
{
    /**
     * @var array массив данных для рендеринга
     */
    private $dataArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы
     * @param array $request
     */
    public function handle($request)
    {
        try {
           $form = new CommentForm(['scenario'=>CommentForm::GET]);
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $finder = \Yii::$app->registry->get(CommentIdFinder::class, [
                        'id'=>$form->id
                    ]);
                    $commentsModel = $finder->find();
                    if (empty($commentsModel)) {
                        throw new ErrorException($this->emptyError('commentsModel'));
                    }
                    
                    $commentForm = new CommentForm();
                    
                    $adminCommentFormWidgetConfig = $this->adminCommentFormWidgetConfig($commentsModel, $commentForm);
                    
                    return AdminCommentFormWidget::widget($adminCommentFormWidgetConfig);
                }
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает массив конфигурации для виджета AdminCommentFormWidget
     * @param Model $commentsModel
     * @param AbstractBaseForm $commentForm
     * @return array
     */
    private function adminCommentFormWidgetConfig(Model $commentsModel, AbstractBaseForm $commentForm): array
    {
        try {
            $dataArray = [];
            
            $dataArray['comment'] = $commentsModel;
            $dataArray['form'] = $commentForm;
            $dataArray['template'] = 'admin-comment-form.twig';
            
            return $dataArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
