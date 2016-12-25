<?php

namespace app\services;

use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\helpers\{ArrayHelper,
    Url};
use app\services\{AbstractBaseService,
    FrontendTrait};
use app\finders\{BrandsFilterFinder,
    CategorySeocodeFinder,
    ColorsFilterFinder,
    FiltersSessionFinder,
    ProductsFinder,
    SizesFilterFinder,
    SortingFieldsFinder,
    SortingTypesFinder,
    SubcategorySeocodeFinder};
use app\helpers\HashHelper;
use app\forms\FiltersForm;
use app\filters\ProductsFilters;
use app\collections\ProductsCollection;

/**
 * Формирует массив данных для рендеринга страницы каталога товаров
 */
class ProductsListIndexService extends AbstractBaseService
{
    use FrontendTrait;
    
    /**
     * @var ProductsFilters объект текущих фильтров
     */
    private $filtersModel = null;
    /**
     * @var ProductsCollection коллекция товаров
     */
    private $productsCollection = null;
    /**
     * @var array данные товаров
     */
    private $productsArray = [];
    /**
     * @var array данные пагинации
     */
    private $paginationArray = [];
    /**
     * @var array данные breadcrumbs
     */
    private $breadcrumbsArray = [];
    /**
     * @var array данные товарных фильтров
     */
    private $filtersArray = [];
    
    /**
     * Обрабатывает запрос на поиск данных для 
     * формирования HTML страницы каталога товаров
     * @param array $request
     */
    public function handle($request): array
    {
        try {
            $dataArray = [];
            
            $dataArray = array_merge($dataArray, $this->getUserArray());
            $dataArray = array_merge($dataArray, $this->getCartArray());
            $dataArray = array_merge($dataArray, $this->getCurrencyArray());
            $dataArray = array_merge($dataArray, $this->getSearchArray());
            $dataArray = array_merge($dataArray, $this->getCategoriesArray());
            
            $dataArray = array_merge($dataArray, $this->getProductsArray($request));
            $dataArray = array_merge($dataArray, $this->getPaginationArray($request));
            $dataArray = array_merge($dataArray, $this->getBreadcrumbsArray($request));
            $dataArray = array_merge($dataArray, $this->getFiltersArray($request));
            
            return $dataArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает модель товарных фильтров
     * @return ProductsFilters
     */
    private function getFiltersModel(): ProductsFilters
    {
        try {
            if (empty($this->filtersModel)) {
                $finder = new FiltersSessionFinder([
                    'key'=>HashHelper::createFiltersKey(Url::current())
                 ]);
                $filtersModel = $finder->find();
                
                if (empty($filtersModel)) {
                    throw new ErrorException($this->emptyError('filtersModel'));
                }
                
                $this->filtersModel = $filtersModel;
            }
            
            return $this->filtersModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает коллекцию товаров
     * @param array $request массив данных запроса
     * @return ProductsCollection
     */
    private function getProductsCollection(array $request): ProductsCollection
    {
        try {
            if (empty($this->productsCollection)) {
                $finder = new ProductsFinder([
                    'category'=>$request[\Yii::$app->params['categoryKey']] ?? null,
                    'subcategory'=>$request[\Yii::$app->params['subcategoryKey']] ?? null,
                    'page'=>$request[\Yii::$app->params['pagePointer']] ?? 0,
                    'filters'=>$this->getFiltersModel()
                ]);
                $this->productsCollection = $finder->find();
            }
            
            return $this->productsCollection;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные для вывода списка товаров
     * @param array $request массив данных запроса
     * @return array
     */
    private function getProductsArray(array $request): array
    {
        try {
            if (empty($this->productsArray)) {
                $dataArray = [];
                
                $productsCollection = $this->getProductsCollection($request);
                
                if ($productsCollection->isEmpty() === true) {
                    if ($productsCollection->pagination->totalCount > 0) {
                        throw new NotFoundHttpException($this->error404());
                    }
                    $dataArray['emptyConfig']['view'] = 'empty-products.twig';
                } else {
                    $dataArray['productsConfig']['products'] = $productsCollection;
                    $dataArray['productsConfig']['currency'] = $this->getCurrencyModel();
                    $dataArray['productsConfig']['view'] = 'products-list.twig';
                }
                
                $this->productsArray = $dataArray;
            }
            
            return $this->productsArray;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные для вывода пагинации
     * @param array $request массив данных запроса
     * @return array
     */
    private function getPaginationArray(array $request): array
    {
        try {
            if (empty($this->paginationArray)) {
                $dataArray = [];
                
                $pagination = $this->getProductsCollection($request)->pagination;
                
                if (empty($pagination)) {
                    throw new ErrorException($this->emptyError('pagination'));
                }
                
                $dataArray['paginationConfig']['pagination'] = $pagination;
                $dataArray['paginationConfig']['view'] = 'pagination.twig';
                
                $this->paginationArray = $dataArray;
            }
            
            return $this->paginationArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные для вывода breadcrumbs
     * @param array $request массив данных запроса
     * @return array
     */
    private function getBreadcrumbsArray(array $request): array
    {
        try {
            if (empty($this->breadcrumbsArray)) {
                $dataArray = [];
                
                if (!empty($category = $request[\Yii::$app->params['categoryKey']] ?? null)) {
                    $finder = new CategorySeocodeFinder([
                        'seocode'=>$category
                    ]);
                    $categoryModel = $finder->find();
                    if (empty($categoryModel)) {
                        throw new ErrorException($this->emptyError('categoryModel'));
                    }
                    $dataArray['breadcrumbsConfig']['category'] = $categoryModel;
                    
                    if (!empty($subcategory = $request[\Yii::$app->params['subcategoryKey']] ?? null)) {
                        $finder = new SubcategorySeocodeFinder([
                            'seocode'=>$subcategory
                        ]);
                        $subcategoryModel = $finder->find();
                        if (empty($subcategoryModel)) {
                            throw new ErrorException($this->emptyError('subcategoryModel'));
                        }
                        $dataArray['breadcrumbsConfig']['subcategory'] = $subcategoryModel;
                    }
                }
                
                $this->breadcrumbsArray = $dataArray;
            }
            
            return $this->breadcrumbsArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Возвращает данные для вывода фильтров каталога
     * @param array $request массив данных запроса
     * @return array
     */
    private function getFiltersArray(array $request): array
    {
        try {
            if (empty($this->filtersArray)) {
                $dataArray = [];
                
                $category = $request[\Yii::$app->params['categoryKey']] ?? null;
                $subcategory = $request[\Yii::$app->params['subcategoryKey']] ?? null;
                
                $finder = new ColorsFilterFinder([
                    'category'=>$category,
                    'subcategory'=>$subcategory,
                ]);
                $colorsArray = $finder->find();
                if (empty($colorsArray)) {
                    throw new ErrorException($this->emptyError('colorsArray'));
                }
                ArrayHelper::multisort($colorsArray, 'color');
                $dataArray['filtersConfig']['colors'] = ArrayHelper::map($colorsArray, 'id', 'color');
                
                $finder = new SizesFilterFinder([
                    'category'=>$category,
                    'subcategory'=>$subcategory,
                ]);
                $sizesArray = $finder->find();
                if (empty($sizesArray)) {
                    throw new ErrorException($this->emptyError('sizesArray'));
                }
                ArrayHelper::multisort($sizesArray, 'size');
                $dataArray['filtersConfig']['sizes'] = ArrayHelper::map($sizesArray, 'id', 'size');
                
                $finder = new BrandsFilterFinder([
                    'category'=>$category,
                    'subcategory'=>$subcategory,
                ]);
                $brandsArray = $finder->find();
                if (empty($brandsArray)) {
                    throw new ErrorException($this->emptyError('brandsArray'));
                }
                ArrayHelper::multisort($brandsArray, 'brand');
                $dataArray['filtersConfig']['brands'] = ArrayHelper::map($brandsArray, 'id', 'brand');
                
                $finder = new SortingFieldsFinder();
                $sortingFieldsArray = $finder->find();
                if (empty($sortingFieldsArray)) {
                    throw new ErrorException($this->emptyError('sortingFieldsArray'));
                }
                ArrayHelper::multisort($sortingFieldsArray, 'value');
                $dataArray['filtersConfig']['sortingFields'] = ArrayHelper::map($sortingFieldsArray, 'name', 'value');
                
                $finder = new SortingTypesFinder();
                $sortingTypesArray = $finder->find();
                if (empty($sortingTypesArray)) {
                    throw new ErrorException($this->emptyError('sortingTypesArray'));
                }
                ArrayHelper::multisort($sortingTypesArray, 'value');
                $dataArray['filtersConfig']['sortingTypes'] = ArrayHelper::map($sortingTypesArray, 'name', 'value');
                
                $form = new FiltersForm(array_merge(['url'=>Url::current()], array_filter($this->getFiltersModel()->toArray())));
                if (empty($form->sortingField)) {
                    foreach ($sortingFieldsArray as $item) {
                        if ($item['name'] === \Yii::$app->params['sortingField']) {
                            $form->sortingField = $item;
                        }
                    }
                }
                if (empty($form->sortingType)) {
                    foreach ($sortingTypesArray as $item) {
                        if ($item['name'] === \Yii::$app->params['sortingType']) {
                            $form->sortingType = $item;
                        }
                    }
                }
                $dataArray['filtersConfig']['form'] = $form;
                $dataArray['filtersConfig']['view'] = 'products-filters.twig';
                
                $this->filtersArray = $dataArray;
            }
            
            return $this->filtersArray;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
