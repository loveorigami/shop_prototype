<?php

namespace app\mappers;

use app\mappers\AbstractGetMapper;
use yii\base\ErrorException;
use app\models\RulesModel;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
class RulesByRuleMapper extends AbstractGetMapper
{
    /**
     * @var string имя класса, который формирует строку запроса
     */
    public $queryClass = 'app\queries\RulesByRuleQueryCreator';
    /**
     * @var string имя класса, который создает объекты из данных БД
     */
    public $objectsClass = 'app\factories\RulesObjectsFactory';
    
    public function init()
    {
        try {
            parent::init();
            
            if (empty($this->model) || !$this->model instanceof RulesModel) {
                throw new ErrorException('Не определен объект модели, для которой необходимо получить данные!');
            }
            
            if (empty($this->params)) {
                $this->params = [':rule'=>$this->model->rule];
            }
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}