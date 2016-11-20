<?php

namespace app\repository;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;

/**
 * Содержит общую функциональтность для классов репозиториев
 */
abstract class AbstractBaseRepository extends Object
{
    use ExceptionsTrait;
    
    /**
     * Применяет критерии к запросу
     * @param mixed $query запрос, к которому будет применена фильтрация
     */
    public function addCriteria($query)
    {
        try {
            if (!empty($this->criteria)) {
                $query = $this->criteria->filter($query);
            }
            return $query;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
