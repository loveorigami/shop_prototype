<?php

namespace app\finders;

/**
 * Коллекция методов, общих для всех finders
 */
trait BaseTrait
{
    /**
     * Загружает данные в свойства модели
     * @param $data массив данных
     * @return bool
     */
    public function load($data, $formName=null)
    {
        try {
            return parent::load($data, '');
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}