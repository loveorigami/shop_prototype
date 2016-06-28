<?php

namespace app\queries;

use app\queries\AbstractBaseQueryCreator;
use app\mappers\AbstractBaseMapper;

abstract class AbstractSeletcQueryCreator extends AbstractBaseQueryCreator
{
    /**
     * Принимает объект, данные которого необходимо обработать, сохраняет его во внутреннем свойстве, реализуя VisitorInterface
     * запускает процесс
     * @param $object
     */
    public function update(AbstractBaseMapper $object)
    {
        try {
            parent::update($object);
            $this->getSelectQuery();
            return true;
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Инициирует создание SELECT запроса
     */
    public function getSelectQuery()
    {
        try {
            $this->_mapperObject->query = 'SELECT ';
            $this->_mapperObject->query .= $this->addFields();
            $this->_mapperObject->query .= $this->addTableName();
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
