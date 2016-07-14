<?php

$config = [
    'id'=>'shop',
    'basePath'=>__DIR__ . '/../',
    'language'=>'ru-RU',
    'sourceLanguage'=>'en-US',
    'timeZone'=>'Europe/Kiev',
    'layout'=>'main.twig',
    'bootstrap'=>['log'],
    'params'=>[
        'filterKeys'=>['colors', 'sizes', 'brands'], # Ключи, по которым в $_REQUEST доступны значения выбранных фильтров
        'limit'=>20, # Кол-во записей на страницу
        'pagePointer'=>'page', # Ключ, по которому в $_REQUEST доступен номер текущей страницы
        'defaultOrderByType'=>'DESC', # Порядок сортировки для БД по умолчанию
        'categoryKey'=>'categories', # Ключ, по которому в $_REQUEST доступно название категории
        'subCategoryKey'=>'subcategory', # Ключ, по которому в $_REQUEST доступно название подкатегории
        'searchKey'=>'search', # Ключ, по которому в $_REQUEST доступно значение для поиска
        'idKey'=>'id', # Ключ, по которому в $_REQUEST доступно значение id продукта
        'fixSentRequests'=>0, #Количество запросов к БД при выполнении скрипта
        'cartKeyInSession'=>'cart', # Ключ, по которому в $_SESSION доступена переменная, хранящая купленные товары
        'filtersKeyInSession'=>'filters', # Ключ, по которому в $_SESSION доступена переменная, хранящая выбранные фильтры
        'defaultRulesId'=>[1, 4], # Id прав доступа, назначаемых при регистрации пользователя по-умолчанию
        'userFromFormForAuthentication'=>NULL, # Объект пользователя в процессе аутентификации
        'usersKeyInSession'=>'user', # Ключ, по которому в $_SESSION доступена переменная, пользователя
        'nonAuthenticatedUserLogin'=>'Guest', # логин не аутентифицированного пользователя, доступный в \Yii::$app->user по умолчанию
        'filedsFromDb'=>['id', 'login', 'password', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'], # список полей, которые необходимо получит из БД при аутентификации, используется объектами UserAuthenticationHelper, LoginExistsValidator, PasswordExistsValidator
        'filedsToUser'=>['id', 'login', 'name', 'surname', 'id_emails', 'id_phones', 'id_address'], # список полей, которые необходимо обновить для \Yii::$app->user при аутентификации, используется объектами UserAuthenticationHelper
    ],
    'components'=>[
        'db'=>require(__DIR__ . '/db.php'),
        'view'=>[
            'class'=>'yii\web\View',
            'renderers'=>[
                'twig'=>[
                    'class'=>'yii\twig\ViewRenderer',
                    'options'=>['auto_reload'=>true],
                    'globals'=>[
                        'html'=>'yii\helpers\Html',
                        'array'=>'yii\helpers\ArrayHelper',
                        'url'=>'yii\helpers\Url',
                        'objectsToArrayHelper'=>'app\helpers\ObjectsToArrayHelper',
                    ],
                ]
            ],
        ],
        'request'=>[
            'cookieValidationKey'=>md5('sLkuN'),
        ],
        'log'=>[
            'traceLevel'=>YII_DEBUG ? 3 : 0,
            'flushInterval'=>100,
            'targets'=>[
                'file'=>[
                    'class'=>'yii\log\FileTarget',
                    'logFile'=>__DIR__ . '/../logs/error.log',
                    'levels'=>['error', 'warning'],
                    'exportInterval'=>100,
                ],
            ],
        ],
        'assetManager'=>[
            'bundles'=>[
                'yii\web\JqueryAsset'=>[
                    'sourcePath'=>null,
                    'js'=>['https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js']
                ],
            ],
            'basePath'=>'@webroot/sources/temp',
            'baseUrl'=>'@web/sources/temp',
            'appendTimestamp'=>true
        ],
        'urlManager'=>[
            'enablePrettyUrl'=>true,
            'showScriptName'=>false,
            'enableStrictParsing' => false,
            'rules'=>[
                'products/<categories>/<subcategory>/<id:\d+>'=>'product-detail/index',
                'products/<categories>/<subcategory>'=>'products-list/index',
                'products/<categories>'=>'products-list/index',
                'products'=>'products-list/index',
                'add-filters'=>'filter/add-filters',
                'clean-filters'=>'filter/clean-filters',
                'currency-filter'=>'currency/set-currency',
                'search'=>'products-list/search',
                'join'=>'users/add-user',
                'login'=>'users/login-user',
                'logout'=>'users/logout-user',
                'add-comment'=>'comments/add-comment',
                'add-to-cart'=>'shopping-cart/add-to-cart',
                'clear-cart'=>'shopping-cart/clear-cart',
                'remove-product'=>'shopping-cart/remove-product',
                'update-product'=>'shopping-cart/update-product',
                'shopping-cart'=>'shopping-cart/index',
                'shopping-cart-checkout'=>'shopping-cart/address-contacts',
                'shopping-cart-check-pay'=>'shopping-cart/check-pay',
                'shopping-cart-pay'=>'shopping-cart/pay',
            ],
        ],
        'cart'=>[
            'class'=>'app\cart\ShoppingCart',
        ],
        'filters'=>[
            'class'=>'app\models\FiltersModel',
        ],
        'user'=>[
            'class'=>'app\models\UsersModel',
        ],
        'session'=>[
            'class'=>'yii\web\DbSession',
            'timeout'=>60*60*24*7
        ],
        'mailer'=>[
            'class'=>'yii\swiftmailer\Mailer',
            'viewPath'=>'@app/views/mail',
        ],
    ],
    'as shoppingCartFilter'=>['class'=>'app\filters\ShoppingCartFilter'],
    'as usersFilter'=>['class'=>'app\filters\UsersFilter'],
];

if (YII_DEBUG) {
    $config['as checkScriptInfoFilter'] = ['class'=>'app\filters\CheckScriptInfoFilter'];
}

return $config;
