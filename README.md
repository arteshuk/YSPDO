# YSPDO
Class in PHP `YSPDO` is a `PDO` helper to manipulate dynamically database records using `arrays`
### Initialize class

```php
$yspdo = new YSPDO([
  'mysql',  // Driver
  'host'    => 'localhost',
  'port'    => 3306,
  'dbname'  => 'generatedata'
],'root','');
```
## CREATE | READ | UPDATE | DELETE
#### CREATE
```php
$stmt = $yspdo->insert('peoples',[
  'name'    => 'Gabriel Almeida',
  'email'   => 'gabrieel@email.com',
  'country' => 'Brazil'
]);

echo $stmt->lastInsertId();
```
#### READ
```php
$stmt = $yspdo->select('peoples',['name','email','address','company'],[
  'id{>=}' => 100
]);

print_r( $stmt->fetchAll('COLUMN') );
```
#### UPDATE
```php
$stmt = $yspdo->update('peoples',[
  'phone' => '(00) 0000-0000',
  'address' => '4129 Magna. Avenue',
],[
  'id' => 100
]);
```
#### DELETE
```php
$stmt = $yspdo->delete('peoples',[
  'id' => 100
]);
```
## FUNCTION SELECT
#### fetch
```php
$yspdo->select('peoples','*',[
    'email' => 'commodo@sem.edu'
])->fetch();
```
#### fetchAll
```php
$yspdo->select('peoples')->fetchAll();
```
#### fetch setting type of return
```php
$yspdo->select('peoples','*',[
    'email' => 'commodo@sem.edu'
])->fetch('OBJ');
```

#### fetch all, defining type of return
```php
$yspdo->select('peoples')->fetchAll('OBJ');
```

#### Selecting columns
```php
$yspdo->select('peoples',['name','email','phone'])->fetchAll();
```
#### DISTINCT
```php
$yspdo->select('peoples',[
    'DISTINCT' => 'name'
    // OR
    'DISTINCT' => ['name','phone','date']
])->fetchAll();
```

#### ALIASES
```php
$yspdo->select('peoples',[
    'AS' => [
        'name' => 'yourName',
        'date' => 'birthday',
        'email' => 'contact'
    ]
])->fetchAll();
```

#### Operators
```php
$yspdo->select('peoples','*',[
    'id{>=}' => 100
])->fetchAll();
```

#### rowCount
```php
$yspdo->select('peoples','*',[
    'email' => 'dolor.sit@ametdiam.ca'
])->rowCount();
```
#### ORDER BY
```php
$yspdo->select('peoples','*',[
    'ORDER' => 'city'
    // OR
    'ORDER' => 'name DESC'
    // OR
    'ORDER' => ['name DESC','city ASC']
])->fetchAll();
```
#### BETWEEN
```php
$yspdo->select('peoples','*',[
    'BETWEEN' => [
        'id' => [1,25]
        // OR
        'name' => ['a','b']
    ]
])->fetchAll();
```
#### NOT BETWEEN
```php
$yspdo->select('peoples','*',[
    '!BETWEEN' => [
        'id' => [1,25]
        // OR
        'name' => ['a','b']
    ]
])->fetchAll();
```
#### LIKE
```php
$yspdo->select('peoples','*',[
    'LIKE' => [
        'name' => 'g%'
    ]
])->fetchAll();
```
#### NOT LIKE
```php
$yspdo->select('peoples','*',[
    '!LIKE' => [
        'name' => 'g%'
    ]
])->fetchAll();
```
#### LIMIT
```php
$yspdo->select('peoples','*',[
    'LIMIT' => 10
])->fetchAll()
```
#### IN
```php
$yspdo->select('peoples','*',[
    'IN' => [
        'city' => ['Acoz','Pietraroja','Martelange','Relegem']
    ]
])->fetchAll();
```
#### NOT IN
```php
$yspdo->select('peoples','*',[
    '!IN' => [
        'city' => ['Acoz','Pietraroja','Martelange','Relegem']
    ]
])->fetchAll();
```
#### Using IN | BETWEEN | NOT LIKE | ORDER BY
```php
$yspdo->select('peoples','*',[
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
$yspdo->createDB('dbname');
```
### DELETE DATABASE
```php
$yspdo->deleteDB('dbname');
```
### INSERT INTO
```php
$yspdo->insert('peoples',[
    'id'        => 301,
    'name'      => 'Gabriel',
    'phone'     => '+0000000000',
    'date'      => '00/00/0000',
    'email'     => 'name@test.com',
    'address'   => 'Street teste address nº 1000 Av test',
    'city'      => 'an_city',
    'country'   => 'Brazil',
    'company'   => 'No Company',
]);
```
### UPDATE
```php
$yspdo->update('peoples',[
    'phone' => '+1111111111',
    'date' => '11/11/1111',
    'email' => 'name@server.co',
    'address' => 'Street teste address nº 1000 Av test'
],[ 'id' => 301 ]);
```
### DELETE
```php
$yspdo->delete('peoples',[
    'id' => 301
]);
```
### COUNT
```php
$yspdo->count('peoples','*',[
    'id{>}' => 125
]);
```
### DELETE TABLE
```php
$yspdo->deleteTable('peoples');
```
### TRUNCATE TABLE
```php
$yspdo->truncate('peoples');
```
***
### Attention
#### Do not use the (select|insert|update|delete|count|prepare) functions like this:
```php
$statement1 = $yspdo->(select|insert|update|delete|count|prepare)('table_one' ...);
$statement2 = $yspdo->(select|insert|update|delete|count|prepare)('table_two' ...);

print_r( $statement1->fetchAll() );
print_r( $statement2->fetchAll() );

// YSPDO will replace the query from $statement1 with $statement2
```
***
### Available Functions
```php
/**
* PDO::getAvailableDrivers
*
* @return array
*/
->getAvailableDrivers()
/**
* PDO::commit
*
* @return boolean
*/
->commit()
/**
* PDO::beginTransaction
*
* @return boolean
*/
->beginTransaction()
/**
* PDO::rollBack
*
* @return boolean
*/
->rollBack()
/**
* PDO::inTransaction
*
* @return boolean
*/
->inTransaction()
/**
* PDO::exec
*
* @param string $statement
* @return integer|boolean
*/
->exec( string $statement )
/**
* PDO::quote
*
* @param string $string
* @param int $parameter_type
* @return string
*/
->quote($string [, $parameter_type=\PDO::PARAM_STR])
/**
* PDO::errorCode
*
* @return mixed
*/
->errorCode()
/**
* PDO::errorInfo
*
* @return array
*/
->errorInfo()
/**
* Query Statement
*
* @param string $sql
* @param array $parameters
* @return YSPDO
*/
->query(string $sql [, array $parameters=[]])
/**
* Row count
*
* @return integer
*/
->rowCount()
/**
* Last insert id
*
* @return integer
*/
->lastInsertId()
/**
* Prepare Statement
*
* @param string $sql
* @return YSPDO
*/
->prepare(string $sql)
/**
* bindColumn Statement
*
* @param mixed $column
* @param mixed $param
* @param int $type
* @param int $maxlen
* @param mixed $driverdata
* @return YSPDO
*/
->bindColumn(mixed $column , mixed &$param [, int $type [, int $maxlen [, mixed $driverdata ]]])
/**
* bindParam Statement
*
* @param mixed $parameter
* @param mixed $value
* @param int $type
* @param int $length
* @return YSPDO
*/
->bindParam(mixed $parameter , mixed &$variable [, int $data_type = PDO::PARAM_STR [, int $length [, mixed $driver_options ]]])
/**
* bindValue Statement
*
* @param mixed $parameter
* @param mixed $value
* @param int $type
* @return YSPDO
*/
->bindValue(mixed $parameter , mixed $value [, int $data_type = PDO::PARAM_STR ])
/**
* closeCursor Statement
*
* @return boolean
*/
->closeCursor()
/**
* columnCount Statement
*
* @return int
*/
->columnCount()
/**
* execure Statement
*
* @param array $parameters
* @return boolean
*/
->execute([array $parameters=[]])
/**
* fetch Statement
*
* @param string $style
* @param int $cursor_orientation
* @param int $offset
* @return mixed
*/
->fetch([ string $style [, int $cursor_orientation = PDO::FETCH_ORI_NEXT [, int $cursor_offset = 0 ]]])
/**
* fetchAll Statement
*
* @param string $style
* @param mixed $argument
* @param array $ctor_args
* @return mixed
*/
->fetchAll([ string $style [, mixed $argument = \PDO::FETCH_COLUMN [, array $ctor_args = [] ]]])
/**
* fetchColumn Statement
*
* @param int $column_number
* @return midex
*/
->fetchColumn([int $column_number = 0])
/**
* fetchObject Statement
*
* @param string $class_name
* @param array $ctor_args
* @return midex
*/
->fetchObject([ string $class_name = "stdClass" [, array $ctor_args = [] ]])
/**
* getAttribute Statement
*
* @param int $attribute
* @return mixed
*/
->getAttribute(int $attribute)
/**
* setAttribute Statement
*
* @param int $attribute
* @param mixed $value
* @return boolean
*/
->setAttribute(int $attribute , mixed $value)
/**
* SELECT
*
* @param string       $table
* @param string|array $columns
* @param array|null   $where
* @return YSPDO
*/
->select(string $table [,$columns='*' [,$where=null]])
/**
* INSERT
*
* @param string $table
* @param array  $data
* @return YSPDO
*/
->insert(string $table, array $data)
/**
* UPDATE
*
* @param string $table
* @param array  $data
* @param array|null  $where
* @return YSPDO
*/
->update(string $table, array $data [, $where=null])
/**
* DELETE
*
* @param string $table
* @param array  $where
* @return YSPDO
*/
->delete(string $table [, array $where=''])
/**
*  COUNT
*
* @param string $table
* @param string|array $columns
* @param array $where
* @return string
*/
->count(string $table [, $columns = '*' [, array $where = []]])
/**
* Create Database
*
* @param string $s Database name
* @return boolean
*/
->createDB(string $s)
/**
* Drop Database
*
* @param string $s Database name
* @return boolean
*/
->deleteDB(string $s)
/**
* Drop Table
*
* @param string $s Table name
* @return boolean
*/
->deleteTable(string $s)
/**
* Truncate Table
*
* @param string $s Table name
* @return boolean
*/
->truncate(string $s)
```
***
## REQUIREMENTS
PHP Version 7.x or newer
## LICENCE
[MIT](/LICENCE.txt)
