# YSPDO
Class in PHP `YSPDO` is a `PDO` helper to manipulate dynamically database records using `arrays`
### Initialize class

```php
$db = new YSPDO([
  'mysql',  // First driver | without to set key of this value
  'host'    => 'localhost',
  'port'    => 3306,
  'dbname'  => 'generatedata'
],'root','');
```
## Compare `PDO` and `YSPDO` with `CRUD`
#### Start connection
```php
// PDO
$pdo = new PDO('DRIVER:host=HOST;dbname=DB_NAME;port=PORT', 'DB_USER', 'DB_PASSWORD');

// YSPDO
$db = new YSPDO([
  'DRIVER',
  'host'    => 'HOST',
  'port'    => PORT,
  'dbname'  => 'DB_NAME'
], 'DB_USER', 'DB_PASSWORD');
```
#### CREATE
```php
// PDO
$stmt = $pdo->prepare('INSERT INTO `peoples` (`name`, `email`, `country`) VALUES (?, ?, ?)');
$stmt->bindValue(1, 'Gabriel Almeida', PDO::PARAM_STR);
$stmt->bindValue(2, 'gabrieel@email.com', PDO::PARAM_STR);
$stmt->bindValue(3, 'Brazil', PDO::PARAM_STR);
$stmt->execute();
echo $stmt->lastInsertId();

// YSPDO
$stmt = $db->insert('peoples',[
  'name'    => 'Gabriel Almeida',
  'email'   => 'gabrieel@email.com',
  'country' => 'Brazil'
]);
echo $stmt->lastInsertId();
```
#### READ
```php
// PDO
$stmt = $pdo->prepare('SELECT `name`,`email`,`address`,`company` FROM `peoples` WHERE id>=?');
$stmt->bindValue(1, 100, PDO::PARAM_INT);
$stmt->execute();

print_r( $stmt->fetchAll(PDO::FETCH_COLUMN) );

// YSPDO
$stmt = $db->select('peoples',['name','email','address','company'],[
  'id{>=}' => 100
]);

print_r( $stmt->fetchAll('COLUMN') );

```
#### UPDATE
```php
// PDO
$stmt = $pdo->prepare('UPDATE `peoples` SET `phone`=?, `address`=? WHERE id=?');
$stmt->bindValue(1, '(00) 0000-0000', PDO::PARAM_STR);
$stmt->bindValue(2, '4129 Magna. Avenue', PDO::PARAM_STR);
$stmt->bindValue(3, 100, PDO::PARAM_INT);
$stmt->execute();

// YSPDO
$stmt = $db->update('peoples',[
  'phone' => '(00) 0000-0000',
  'address' => '4129 Magna. Avenue',
],[
  'id' => 100
]);
```
#### DELETE
```php
// PDO
$stmt = $pdo->prepare('DELETE FROM `peoples` WHERE id=?');
$stmt->bindValue(1, 100, PDO::PARAM_INT);
$stmt->execute();

// YSPDO
$stmt = $db->delete('peoples',[
  'id' => 100
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
PHP Version 7.x or newer
## LICENCE
[MIT](/LICENCE.txt)
