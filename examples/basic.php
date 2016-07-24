<?php


include "../dist/YSPDO.php";


$db = new YSPDO([
  'driver'    => 'mysql',
  'host'      => 'localhost',
  'database'  => 'generatedata',
  'user'      => 'root',
  'password'  => '4575621'
]);


$table = 'peoples';


// Selection all columns
//
$query = $db->select( $table )->fetchAll();


// Selecting columns
//
// $query = $db->select( $table ,['name','email','phone'])->fetchAll();


// Fetch
//
// $query = $db->select( $table ,'all',[
//   'email' => 'commodo@sem.edu'
// ])->fetch();

// Fetch all
//
// $query = $db->select( $table )->fetchAll();
//
// Fetch, with type of return
//
// $query = $db->select( $table ,'all',[
//   'email' => 'commodo@sem.edu'
// ])->fetch('OBJ');
//
// Fetch all, with type of return
//
// $query = $db->select( $table )->fetchAll('OBJ');


// DISTINCT
//
// $query = $db->select( $table ,[
//   // 'DISTINCT' => 'name'
//   // OR
//   // 'DISTINCT' => ['name','phone','date']
// ])->fetchAll();


// ALIASES
//
// $query = $db->select( $table ,[
//   'AS' => [
//     'name' => 'yourName',
//     'date' => 'birthday',
//     'email' => 'contact'
//   ]
// ])->fetchAll();


// Operators
//
// $query = $db->select( $table ,'all',[
//   'id{>=}' => 100
// ])->fetchAll();



// Row count
//
// $query = $db->select( $table ,'all',[
//   'email' => 'dolor.sit@ametdiam.ca'
// ])->rowCount();


// ORDER BY
//
// $query = $db->select( $table ,'all',[
//   // 'ORDER' => 'city'
//   // OR
//   // 'ORDER' => 'name DESC'
//   // OR
//   // 'ORDER' => ['name DESC','city ASC']
// ])->fetchAll();


// NOT BETWEEN
//
// $query = $db->select( $table ,'all',[
//   '!BETWEEN' => [
//     'id' => [1,275]
//     // OR
//     // 'name' => ['a','b']
//     // OR
//     // 'date' => ['05/04/1980','05/04/1990']
//   ]
// ])->fetchAll();


// BETWEEN
//
// $query = $db->select( $table ,'all',[
//   'BETWEEN' => [
//     // 'id' => [1,25]
//     // OR
//     // 'name' => ['a','b']
//     // OR
//     // 'date' => ['05/04/1980','05/04/1990']
//   ]
// ])->fetchAll();


// NOT LIKE
//
// $query = $db->select( $table ,'all',[
//   '!LIKE' => [
//     'name' => 'g%'
//   ]
// ])->fetchAll();

// LIKE
//
// $query = $db->select( $table ,'all',[
//   'LIKE' => [
//     'city' => '%es%'
//   ]
// ])->fetchAll();


// LIMIT
//
// $query = $db->select( $table ,'all',[
//   'LIMIT' => 10
// ])->fetchAll();


// IN
//
// $query = $db->select( $table ,'all',[
//   'IN' => [
//     'city' => ['Acoz','Pietraroja','Martelange','Relegem']
//   ]
// ])->fetchAll();

// NOT IN
//
// $query = $db->select( $table ,'all',[
//   '!IN' => [
//     'city' => ['Acoz','Pietraroja','Martelange','Relegem']
//   ]
// ])->fetchAll();



// IN | BETWEEN | NOT LIKE | ORDER BY
//
// $query = $db->select( $table ,'all',[
//   'IN' => [
//     'city' => ['Zeist','Stafford','Remagne','Istres','Warwick','Urbe','Chatillon','Rawalpindi']
//   ],
//   'BETWEEN' => [
//     'id' => [1,250]
//   ],
//   '!LIKE' => [
//     'city' => 's%'
//   ],
//   'ORDER' => 'city ASC'
// ])->fetchAll();


// CREATE DATABASE
//
// $query = $db->createDB('dbname');


// DELETE DATABASE
//
// $query = $db->deleteDB('dbname');


// CREATE TABLE
//
// $query = $db->createTable('teste',[
//   'id' => [
//     'varchar',
//     255,
//     'AUTO_INCREMENT',
//     '!NULL'
//   ],
//   'email' => [
//     'varchar',
//     100,
//     '!NULL',
//     'COMMENT' => 'Comment row'
//   ],
//   'PRIMARY KEY' => ['id']
// ],[
//   'ENGINE' => 'MyISAM',
//   'DEFAULT CHARSET' => 'utf8',
//   'COLLATE' => 'utf8_bin',
//   'STATS_PERSISTENT' => 0,
//   'COMMENT' => 'comment'
// ]);


// INSERT INTO
//
// $query = $db->insert( $table ,[
//
//   'id' => 301,
//   'name' => 'Gabriel',
//   'phone' => '+0000000000',
//   'date' => '00/00/0000',
//   'email' => 'name@test.com',
//   'address' => 'Street teste address nº 1000 Av test',
//   'city' => 'Araguaina',
//   'country' => 'Brazil',
//   'company' => 'No Company',
//
// ]);


// UPDATE
//
// $query = $db->update( $table ,[
//
//   'phone' => '+1111111111',
//   'date' => '11/11/1111',
//   'email' => 'name@server.co',
//   'address' => 'Street teste address nº 1000 Av test'
//
// ],[ 'id' => 301 ]);

// DELETE
//
// $query = $db->delete( $table ,[
//   'id' => 301
// ]);

// DELETE TABLE
//
// $query = $db->deleteTable( $table );

// TRUNCATE TABLE
//
// $query = $db->truncate( $table );


print_r( $query );
