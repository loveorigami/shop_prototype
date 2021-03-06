<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    ConfigHandlerTrait};
use app\forms\AdminUserMailingForm;
use app\savers\ModelSaver;
use app\widgets\{AccountMailingsFormWidget,
    AccountMailingsUnsubscribeWidget};
use app\models\EmailsMailingsModel;
use app\finders\{MailingsEmailFinder,
    MailingsNotEmailFinder};

/**
 * Обрабатывает запрос на добавление подписки
 */
class AccountSubscriptionsAddRequestHandler extends AbstractBaseHandler
{
    use ConfigHandlerTrait;
    
    /**
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new AdminUserMailingForm(['scenario'=>AdminUserMailingForm::SAVE_ACC]);
            $usersModel = \Yii::$app->user->identity;
            $email = $usersModel->email->email;
            
            if ($request->isAjax === true) {
                if ($form->load($request->post()) === true) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    $errors = ActiveForm::validate($form);
                    if (!empty($errors)) {
                        return $errors;
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    try {
                        $emailsMailingsModel = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::SAVE]);
                        $emailsMailingsModel->id_mailing = $form->id;
                        $emailsMailingsModel->id_email = $usersModel->id_email;
                        if ($emailsMailingsModel->validate() === false) {
                            throw new ErrorException($this->modelError($emailsMailingsModel->errors));
                        }
                        
                        $saver = new ModelSaver([
                            'model'=>$emailsMailingsModel
                        ]);
                        $saver->save();
                        
                        $finder = \Yii::$app->registry->get(MailingsEmailFinder::class, [
                            'email'=>$email
                        ]);
                        $mailingsArray = $finder->find();
                        
                        $finder = \Yii::$app->registry->get(MailingsNotEmailFinder::class, [
                            'email'=>$email
                        ]);
                        $notMailingsArray = $finder->find();
                        
                        $mailingForm = new AdminUserMailingForm();
                        
                        $dataArray = [];
                        
                        $accountMailingsUnsubscribeWidgetConfig = $this->accountMailingsUnsubscribeWidgetConfig($mailingsArray, $mailingForm);
                        $dataArray['unsubscribe'] = AccountMailingsUnsubscribeWidget::widget($accountMailingsUnsubscribeWidgetConfig);
                        
                        $accountMailingsFormWidgetConfig = $this->accountMailingsFormWidgetConfig($notMailingsArray, $mailingForm);
                        $dataArray['subscribe'] = AccountMailingsFormWidget::widget($accountMailingsFormWidgetConfig);
                        
                        $transaction->commit();
                        
                        return $dataArray;
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
