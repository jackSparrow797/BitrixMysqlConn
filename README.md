# BitrixMysqliConnonnector

Коннектор к БД Mysqli, дополняет битриксовский коннектор, но лечит ошибку "2006 MySQL server has gone away". 

## Установка

```bash
$ composer require jack797/bitrix_mysql_connection
```

В `.settings.php` указать параметры `className` и `maxAttempts` по желанию: 

```php
<?php
[
    'connections' => 
      array (
        'value' => 
        array (
          'default' => 
          array (
            'className' => '\\Jack797\\BitrixMysqlConnection\\MysqliConnection',
            'maxAttempts' => 3, //по-умолчанию: 5 (не обязательный параметр)
          ),
        ),
      ),
];
