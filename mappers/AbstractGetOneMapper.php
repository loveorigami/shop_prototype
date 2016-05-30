<?php

namespace app\mappers;

use app\mappers\AbstractBaseMapper;

/**
 * Реализует интерфейс получения массива объектов из базы данных
 */
abstract class AbstractGetOneMapper extends AbstractBaseMapper
{
    /**
     * Возвращает массив объектов, представляющих строки в БД
     * @return array
     */
    public function getOne()
    {
        try {
            $this->run();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
        return $this->objectsOne;
    }
    
    /**
     * Выполняет запрос к базе данных
     * @return array
     */
    protected function getData()
    {
        try {
            $command = \Yii::$app->db->createCommand($this->query);
            $command->bindValue(':' . \Yii::$app->params['idKey'], \Yii::$app->request->get(\Yii::$app->params['idKey']));
            $result = $command->queryOne();
            if (YII_DEBUG) {
                $this->trigger($this::SENT_REQUESTS_TO_DB); # Фиксирует выполнение запроса к БД
            }
            $this->DbArray = $result;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
