<?php

namespace app\forms;

use yii\base\ErrorException;
use app\forms\AbstractBaseForm;
use app\validators\ActiveStatusTypeValidator;

/**
 * Представляет данные формы фильтров для списка комментариев
 */
class AdminCommentsFiltersForm extends AbstractBaseForm
{
    /**
     * Сценарий сохранения значений фильтров
     */
    const SAVE = 'save';
    /**
     * Сценарий обнуления фильтров
     */
    const CLEAN = 'clean';
    
    /**
     * @var string имя столбца, покоторому будут отсортированы результаты
     */
    public $sortingField;
    /**
     * @var string тип сортировки
     */
    public $sortingType;
    /**
     * @var string статус комментария
     */
    public $activeStatus;
    /**
     * @var string URL, с которого была запрошена сортировка
     */
    public $url;
    
    public function scenarios()
    {
        return [
            self::SAVE=>['sortingField', 'sortingType', 'activeStatus', 'url'],
            self::CLEAN=>['url'],
        ];
    }
    
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['activeStatus'], ActiveStatusTypeValidator::class, 'on'=>self::SAVE]
        ];
    }
}
