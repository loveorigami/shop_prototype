<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model,
    Widget};
use yii\helpers\ArrayHelper;
use app\exceptions\ExceptionsTrait;
use app\repositories\RepositoryInterface;
use app\filters\{DistinctFilter,
    FromFilter,
    JoinFilter,
    MatchFilter,
    SelectFilter,
    WhereFilter};
use app\queries\CriteriaInterface;

/**
 * Формирует HTML строку с формой, представляющей фильтры товаров
 */
class FiltersWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var object RepositoryInterface
     */
    public $colorsService;
    /**
     * @var object RepositoryInterface
     */
    public $sizesService;
    /**
     * @var object RepositoryInterface
     */
    public $brandsService;
    /**
     * @var object RepositoryInterface
     */
    public $sphinxService
    /**
     * @var object ActiveRecord/Model, получает данные из формы
     */
    private $form;
    /**
     * @var string имя HTML шаблона
     */
    public $view;
    
    public function init()
    {
        try {
            parent::init();
            
            /*if (empty($this->colorsRepository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('colorsRepository'));
            }
            if (empty($this->sizesRepository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('sizesRepository'));
            }
            if (empty($this->brandsRepository)) {
                throw new ErrorException(ExceptionsTrait::emptyError('brandsRepository'));
            }*/
            if (empty($this->form)) {
                throw new ErrorException(ExceptionsTrait::emptyError('form'));
            }
            if (empty($this->view)) {
                throw new ErrorException(ExceptionsTrait::emptyError('view'));
            }
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    public function run()
    {
        try {
            $renderArray = [];
            
            $renderArray['header'] = \Yii::t('base', 'Filters');
            $renderArray['formModel'] = $this->form;
            $renderArray['sortingFieldsList'] = ['date'=>\Yii::t('base', 'Sorting by date'), 'price'=>\Yii::t('base', 'Sorting by price')];
            $renderArray['sortingTypeList'] = ['SORT_ASC'=>\Yii::t('base', 'Sort ascending'), 'SORT_DESC'=>\Yii::t('base', 'Sort descending')];
            
            if (!empty($this->sphinxRepository)) {
                $criteria = $this->sphinxRepository->criteria;
                $criteria->setFilter(new SelectFilter(['condition'=>['id']]));
                $criteria->setFilter(new FromFilter(['condition'=>'{{shop}}']));
                $criteria->setFilter(new MatchFilter(['condition'=>['fields'=>'[[@* :search]]', 'condition'=>['search'=>\Yii::$app->request->get(\Yii::$app->params['searchKey'])]]]));
                $sphinxCollection = $this->sphinxRepository->getGroup();
            }
            
            $criteria = $this->colorsRepository->criteria;
            $criteria->setFilter(new SelectFilter(['condition'=>['[[colors.id]]', '[[colors.color]]']]));
            $criteria->setFilter(new DistinctFilter());
            $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{products_colors}}', 'condition'=>'[[colors.id]]=[[products_colors.id_color]]']]));
            $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{products}}', 'condition'=>'[[products_colors.id_product]]=[[products.id]]']]));
            $criteria->setFilter(new WhereFilter(['condition'=>['[[products.active]]'=>true]]));
            if (!empty($sphinxCollection)) {
                $criteria->setFilter(new WhereFilter(['condition'=>['[[products.id]]'=>ArrayHelper::getColumn($sphinxCollection, 'id')]]));
            } else {
                $criteria = $this->addCategory($criteria);
            }
            $colorsCollection = $this->colorsRepository->getGroup();
            $colorsCollection = ArrayHelper::map($colorsCollection, 'id', 'color');
            asort($colorsCollection, SORT_STRING);
            $renderArray['colorsCollection'] = $colorsCollection;
           
            $criteria = $this->sizesRepository->criteria;
            $criteria->setFilter(new SelectFilter(['condition'=>['[[sizes.id]]', '[[sizes.size]]']]));
            $criteria->setFilter(new DistinctFilter());
            $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{products_sizes}}', 'condition'=>'[[sizes.id]]=[[products_sizes.id_size]]']]));
            $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{products}}', 'condition'=>'[[products_sizes.id_product]]=[[products.id]]']]));
            $criteria->setFilter(new WhereFilter(['condition'=>['[[products.active]]'=>true]]));
            if (!empty($sphinxCollection)) {
                $criteria->setFilter(new WhereFilter(['condition'=>['[[products.id]]'=>ArrayHelper::getColumn($sphinxCollection, 'id')]]));
            } else {
                $criteria = $this->addCategory($criteria);
            }
            $sizesCollection = $this->sizesRepository->getGroup();
            $sizesCollection = ArrayHelper::map($sizesCollection, 'id', 'size');
            asort($sizesCollection, SORT_STRING);
            $renderArray['sizesCollection'] = $sizesCollection;
            
            $criteria = $this->brandsRepository->criteria;
            $criteria->setFilter(new SelectFilter(['condition'=>['[[brands.id]]', '[[brands.brand]]']]));
            $criteria->setFilter(new DistinctFilter());
            $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{products}}', 'condition'=>'[[products.id_brand]]=[[brands.id]]']]));
            $criteria->setFilter(new WhereFilter(['condition'=>['[[products.active]]'=>true]]));
            if (!empty($sphinxCollection)) {
                $criteria->setFilter(new WhereFilter(['condition'=>['[[products.id]]'=>ArrayHelper::getColumn($sphinxCollection, 'id')]]));
            } else {
                $criteria = $this->addCategory($criteria);
            }
            $brandsCollection = $this->brandsRepository->getGroup();
            $brandsCollection = ArrayHelper::map($brandsCollection, 'id', 'brand');
            asort($brandsCollection, SORT_STRING);
            $renderArray['brandsCollection'] = $brandsCollection;
            
            return $this->render($this->view, $renderArray);
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству FiltersWidget::colorsRepository
     * @param object $repository RepositoryInterface
     */
    public function setColorsRepository(RepositoryInterface $repository)
    {
        try {
            $this->colorsRepository = $repository;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству FiltersWidget::sizesRepository
     * @param object $repository RepositoryInterface
     */
    public function setSizesRepository(RepositoryInterface $repository)
    {
        try {
            $this->sizesRepository = $repository;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству FiltersWidget::brandsRepository
     * @param object $repository RepositoryInterface
     */
    public function setBrandsRepository(RepositoryInterface $repository)
    {
        try {
            $this->brandsRepository = $repository;
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает RepositoryInterface свойству FiltersWidget::sphinxRepository
     * @param object $repository RepositoryInterface
     */
    public function setSphinxRepository(RepositoryInterface $repository)
    {
        try {
            $this->sphinxRepository = $repository;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает Model свойству FiltersWidget::form
     * @param object $model Model
     */
    public function setForm(Model $form)
    {
        try {
            $this->form = $form;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Добавляет фильры по категории и подкатегории
     * @param object $criteria CriteriaInterface
     */
    private function addCategory(CriteriaInterface $criteria): CriteriaInterface
    {
        try {
            if (!empty($category = \Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
                $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{categories}}', 'condition'=>'[[products.id_category]]=[[categories.id]]']]));
                $criteria->setFilter(new WhereFilter(['condition'=>['[[categories.seocode]]'=>$category]]));
                if (!empty($subcategory = \Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
                    $criteria->setFilter(new JoinFilter(['condition'=>['type'=>'INNER JOIN', 'table'=>'{{subcategory}}', 'condition'=>'[[products.id_subcategory]]=[[subcategory.id]]']]));
                    $criteria->setFilter(new WhereFilter(['condition'=>['[[subcategory.seocode]]'=>$subcategory]]));
                }
            }
            
            return $criteria;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
