<?php

namespace app\services;

use yii\base\ErrorException;
use app\services\{AbstractBaseService,
    FrontendTrait};
use app\forms\ChangeCurrencyForm;
use app\helpers\HashHelper;
use app\savers\SessionModelSaver;
use app\finders\CurrencyIdFinder;

/**
 * Сохраняет изменения текущей валюты
 */
class ChangeCurrencySaveService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * Обрабатывает запрос на изменение текущей валюты
     * @param array $request
     * @return mixed
     */
    public function handle($request)
    {
        try {
            $form = new ChangeCurrencyForm(['scenario'=>ChangeCurrencyForm::SET]);
            
            if ($request->isPost === false) {
                throw new ErrorException($this->invalidError('request'));
            }
            if ($form->load($request->post()) === false) {
                throw new ErrorException($this->emptyError('post'));
            }
            if ($form->validate() === false) {
                throw new ErrorException($this->modelError($form->errors));
            }
            
            $finder = new CurrencyIdFinder([
                'id'=>$form->id
            ]);
            $currencyModel = $finder->find();
            if (empty($currencyModel)) {
                throw new ErrorException($this->emptyError('currencyModel'));
            }
            
            $saver = new SessionModelSaver([
                'key'=>HashHelper::createCurrencyKey(),
                'model'=>$currencyModel
            ]);
            $saver->save();
            
            return $form->url;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
