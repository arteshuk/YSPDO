# YSPDO
Class in PHP `YSPDO` is a `PDO` helper to manipulate dynamically database using `arrays`
### Initialize class
Without defining settings
```php
$db = new YSPDO;
```
Defining settings
```php
$db = new YSPDO([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'port'      => 3306,
    'charset'   => 'utf8',
    'database'  => 'dbname',
    'user'      => 'root',
    'password'  => ''
]);
```
## SELECT
#### fetch
```php
$db->select('peoples','all',[
    'email' => 'commodo@sem.edu'
])->fetch();
```
#### fetchAll
```php
$db->select('peoples')->fetchAll();
```
#### fetch setting type of return
```php
$db->select('peoples','all',[
    'email' => 'commodo@sem.edu'
])->fetch('OBJ');
```

#### fetch all setting type of return
```php
$db->select('peoples')->fetchAll('OBJ');
```

#### Selecting columns
```php
$db->select('peoples',['name','email','phone'])->fetchAll();
```
#### DISTINCT
```php
$db->select('peoples',[
    'DISTINCT' => 'name'
    // OR
    'DISTINCT' => ['name','phone','date']
])->fetchAll();
```

#### ALIASES
```php
$db->select('peoples',[
    'AS' => [
        'name' => 'yourName',
        'date' => 'birthday',
        'email' => 'contact'
    ]
])->fetchAll();
```

#### Operators
```php
$db->select('peoples','all',[
    'id{>=}' => 100
])->fetchAll();
```

#### rowCount
```php
$db->select('peoples','all',[
    'email' => 'dolor.sit@ametdiam.ca'
])->rowCount();
```
#### ORDER BY
```php
$db->select('peoples','all',[
    'ORDER' => 'city'
// OR
    'ORDER' => 'name DESC'
// OR
    'ORDER' => ['name DESC','city ASC']
])->fetchAll();
```
#### BETWEEN
```php
$db->select('peoples','all',[
    'BETWEEN' => [
        'id' => [1,25]
        // OR
        'name' => ['a','b']
        // OR
        'date' => ['05/04/1980','05/04/1990']
    ]
])->fetchAll();
```
#### NOT BETWEEN
```php
$db->select('peoples','all',[
    '!BETWEEN' => [
        'id' => [1,25]
        // OR
        'name' => ['a','b']
        // OR
        'date' => ['05/04/1980','05/04/1990']
    ]
])->fetchAll();
```
#### LIKE
```php
$db->select('peoples','all',[
    'LIKE' => [
        'name' => 'g%'
    ]
])->fetchAll();
```
#### NOT LIKE
```php
$db->select('peoples','all',[
    '!LIKE' => [
        'name' => 'g%'
    ]
])->fetchAll();
```
#### LIMIT
```php
$db->select('peoples','all',[
    'LIMIT' => 10
])->fetchAll()
```
#### IN
```php
$db->select('peoples','all',[
    'IN' => [
        'city' => ['Acoz','Pietraroja','Martelange','Relegem']
    ]
])->fetchAll();
```
#### NOT IN
```php
$db->select('peoples','all',[
    '!IN' => [
        'city' => ['Acoz','Pietraroja','Martelange','Relegem']
    ]
])->fetchAll();
```
#### Using IN | BETWEEN | NOT LIKE | ORDER BY
```php
$db->select('peoples','all',[
    'IN' => [
        'date' => ['02/11/1985','11/27/1997','09/24/1969','01/15/1985','09/12/1986']
    ],
    'BETWEEN' => [
        'id' => [1,50]
    ],
    '!LIKE' => [
        'city' => 's%'
    ],
    'ORDER' => 'city ASC'
])->fetchAll();
```
### CREATE DATABASE
```php
$db->createDB('dbname');
```
### DELETE DATABASE
```php
$db->deleteDB('dbname');
```
### CREATE TABLE
```php
$db->createTable('teste',[
    'id' => [
        'varchar',
        255,
        'AUTO_INCREMENT',
        '!NULL'
    ],
    'email' => [
        'varchar',
        100,
        '!NULL',
        'COMMENT' => 'Comment row'
    ],
    'PRIMARY KEY' => ['id']
],[
    'ENGINE' => 'MyISAM',
    'DEFAULT CHARSET' => 'utf8',
    'COLLATE' => 'utf8_bin',
    'STATS_PERSISTENT' => 0,
    'COMMENT' => 'comment'
]);
```
### INSERT INTO
```php
$db->insert('peoples',[
    'id' => 301,
    'name' => 'Gabriel',
    'phone' => '+0000000000',
    'date' => '00/00/0000',
    'email' => 'name@test.com',
    'address' => 'Street teste address nº 1000 Av test',
    'city' => 'Araguaina',
    'country' => 'Brazil',
    'company' => 'No Company',
]);
```
### UPDATE
```php
$db->update('peoples',[
    'phone' => '+1111111111',
    'date' => '11/11/1111',
    'email' => 'name@server.co',
    'address' => 'Street teste address nº 1000 Av test'
],[ 'id' => 301 ]);
```
### DELETE
```php
$db->delete('peoples',[
    'id' => 301
]);
```
### DELETE TABLE
```php
$db->deleteTable('peoples');
```
### TRUNCATE TABLE
```php
$db->truncate('peoples');
```
### Available Functions
```php
->getAvailableDrivers()
->commit()
->beginTransaction()
->rollBack()
->inTransaction()
->exec()
->quote()
->errorCode()
->errorInfo()
->query()
->rowCount()
->lastInsertId()
->prepare()
->bindColumn()
->bindParam()
->bindValue()
->closeCursor()
->columnCount()
->execute()
->fetch()
->fetchAll()
->fetchColumn()
->fetchObject()
->getAttribute()
->setAttribute()
->select()
->insert()
->update()
->delete()
->createDB()
->deleteDB()
->createTable()
->deleteTable()
->truncate()
```
***
## REQUIREMENTS
PHP Version >= 7
## QUESTIONS AND FEEDBACK
Email: gabrieel@email.com
## LICENCE
MIT
***
This README was translated automatically from `Brazilian Portuguese` to `English`
