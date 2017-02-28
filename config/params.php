<?php

define('ACTIVE_STATUS', 1);
define('INACTIVE_STATUS', 0);

$params = [
    # Вывод записей на страницу
    'limit'=>3, # Кол-во записей на страницу
    'pagePointer'=>'page', # Ключ, по которому в $_REQUEST доступен номер текущей страницы
    'similarLimit'=>3, # Кол-во похожих товаров
    'popularLimit'=>10, # Кол-во популярных товаров для админ раздела
    'visitorsLimit'=>5, # Кол-во дней, по котором вернуть статистику для админ раздела
    'todayOrdersLimit'=>5, # Кол-во дней, по котором вернуть статистику для админ раздела
    
    # Фильтры
    'filterKeys'=>['colors', 'sizes', 'brands'], # Ключи, по которым доступны значения фильтров
    'sortingField'=>'date', # Поле сортировки по умолчанию
    'sortingFieldUsers'=>'id', # Поле сортировки пользователей по умолчанию
    'sortingType'=>SORT_DESC, # Тип сортировки по умолчанию
    'sortingFieldOrders'=>'received_date', # Поле сортировки заказов по умолчанию
    'sortingTypeOrders'=>SORT_DESC, # Тип сортировки заказов по умолчанию
    'ordersFilters'=>'ordersFilters', # Ключ, по которому в $_SESSION доступны фильтры заказов админ раздела
    'adminProductsFilters'=>'adminProductsFilters', # Ключ, по которому в $_SESSION доступны фильтры товаров админ раздела
    'usersFilters'=>'usersFilters', # Ключ, по которому в $_SESSION доступны фильтры заказов админ раздела
    'commentsFilters'=>'commentsFilters', # Ключ, по которому в $_SESSION доступны фильтры заказов админ раздела
    
    # Путь к товару
    'categoryKey'=>'category', # Ключ, по которому в $_REQUEST доступна текущая категория
    'subcategoryKey'=>'subcategory', # Ключ, по которому в $_REQUEST доступна текущая подкатегория
    'productKey'=>'seocode', # Ключ, по которому в $_REQUEST доступен seocode продукта
    'productId'=>'productId', # Ключ, по которому в $_REQUEST доступен id продукта
    
    # Путь к товару админ раздел
    'orderId'=>'orderId', # Ключ, по которому в $_REQUEST доступен номер запрашиваемого заказа
    
    # Поиск по товарам
    'searchKey'=>'search', # Ключ, по которому в $_REQUEST доступно значение для поиска
    
    # URL запроса
    'urlKey'=>'url', # Ключ, по которому в $_REQUEST доступен URL, с которого был отправлен запрос
    
    # Заказы
    'cartKey'=>'cart', # Ключ, по которому в $_SESSION доступно текущее состояние корзины
    'customerKey'=>'customer', # Ключ, по которому в $_SESSION доступны данные покупателя
    'orderStatuses'=>['received', 'processed', 'canceled', 'shipped'], # Статусы заказа
    
    # Компоненты Breadcrumbs
    'breadcrumbs'=>[],
    
    # Хэш
    'hashSalt'=>'l2WYXNJwH=B*GPW;0R&H', # строка данных, которая передаётся хеш-функции
    
    # Восстановление пароля
    'recoveryKey'=>'recovery', # Ключ, по которому в $_GET доступен ключ для смены пароля
    'emailKey'=>'email', # Ключ, по которому в $_GET доступен email, для которого необходимо сменить пароль
    
    # Валюта
    'currencyKey'=>'currency', # Ключ, по которому в $_SESSION доступны данные текущей валюты
    
    # Формы
    'formFiller'=>'------------------------',
    
    # Изображения
    'maxWidth'=>700, # максимально допустимая ширина сохраняемого изображения
    'maxHeight'=>700, # максимально допустимая высота сохраняемого изображения
    'maxThumbnailWidth'=>400, # максимально допустимая ширина сохраняемого эскиза изображения
    'maxThumbnailHeight'=>400, # максимально допустимая высота сохраняемого эскиза изображения
    'thumbnailPrefix'=>'thumbn_', # префикс эскизов изображений
    
    # Пользователи
    'userKey'=>'user', # Ключ, по которому в $_SESSION доступны данные текущего пользователя
    'userId'=>'id', # Ключ, по которому в $_REQUEST доступны данные текущего пользователя
    'userEmail'=>'email', # Ключ, по которому в $_REQUEST доступны данные текущего пользователя
    
    # Рассылки
    'unsubscribeKey'=>'unsubscribe', # Ключ, по которому в $_SESSION доступен ключ удаления связи пользователя с рассылкой
    
    # Счетчики
    'visitorTimer'=>'visitorTimer', # Ключ, по которому в $_SESSION доступены данные о последнем посещении
];

return $params;
