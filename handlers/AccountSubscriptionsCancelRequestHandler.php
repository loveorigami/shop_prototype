<?php

namespace app\handlers;

use yii\base\ErrorException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\handlers\{AbstractBaseHandler,
    AccountHandlerTrait};
use app\forms\MailingForm;
use app\removers\EmailsMailingsModelRemover;
use app\widgets\{AccountMailingsFormWidget,
    AccountMailingsUnsubscribeWidget};
use app\models\EmailsMailingsModel;

/**
 * Обрабатывает запрос на отмену подписки
 */
class AccountSubscriptionsCancelRequestHandler extends AbstractBaseHandler
{
    use AccountHandlerTrait;
    
    /**
     * Обрабатывает запрос на отмену подписки
     * @param $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new MailingForm(['scenario'=>MailingForm::UNSUBSCRIBE_ACC]);
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
                        $emailsMailingsModel = new EmailsMailingsModel(['scenario'=>EmailsMailingsModel::DELETE]);
                        $emailsMailingsModel->id_mailing = $form->id;
                        $emailsMailingsModel->id_email = $usersModel->id_email;
                        if ($emailsMailingsModel->validate() === false) {
                            throw new ErrorException($this->modelError($emailsMailingsModel->errors));
                        }
                        
                        $remover = new EmailsMailingsModelRemover([
                            'model'=>$emailsMailingsModel
                        ]);
                        $remover->remove();
                        
                        $dataArray = [];
                        
                        $accountMailingsUnsubscribeWidgetConfig = $this->accountMailingsUnsubscribeWidgetConfig($email);
                        $dataArray['unsubscribe'] = AccountMailingsUnsubscribeWidget::widget($accountMailingsUnsubscribeWidgetConfig);
                        
                        $accountMailingsFormWidgetConfig = $this->accountMailingsFormWidgetConfig($email);
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
