<?php


include __DIR__."/../dist/YSPDO.php";


$db = new YSPDO([
  'mysql',
  'host' => 'localhost',
  'dbname' => 'generatedata',
  'port' => 3306,
  'charset' => 'utf8',
],'root','');



$table = 'peoples';


// Selection all columns
//
// $query = $db->select( $table )->fetchAll();


// Selecting columns
//
$query = $db->select( $table ,['name','email','phone'])->fetchAll();


// Fetch
//
// $query = $db->select( $table ,'*',[
//   'email' => 'commodo@sem.edu'
// ])->fetch();

// Fetch all
//
// $query = $db->select( $table )->fetchAll();
//
// Fetch, with type of return
//
// $query = $db->select( $table ,'*',[
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
// $query = $db->select( $table ,'*',[
//   'id{>=}' => 100
// ])->fetchAll();



// Row count
//
// $query = $db->select( $table ,'*',[
//   'email' => 'dolor.sit@ametdiam.ca'
// ])->rowCount();


// ORDER BY
//
// $query = $db->select( $table ,'*',[
//   // 'ORDER' => 'city'
//   // OR
//   // 'ORDER' => 'name DESC'
//   // OR
//   // 'ORDER' => ['name DESC','city ASC']
// ])->fetchAll();


// NOT BETWEEN
//
// $query = $db->select( $table ,'*',[
//   '!BETWEEN' => [
//     'id' => [1,275]
//     // OR
//     // 'name' => ['a','b']
//   ]
// ])->fetchAll();


// BETWEEN
//
// $query = $db->select( $table ,'*',[
//   'BETWEEN' => [
//     // 'id' => [1,25]
//     // OR
//     // 'name' => ['a','b']
//   ]
// ])->fetchAll();


// NOT LIKE
//
// $query = $db->select( $table ,'*',[
//   '!LIKE' => [
//     'name' => 'g%'
//   ]
// ])->fetchAll();

// LIKE
//
// $query = $db->select( $table ,'*',[
//   'LIKE' => [
//     'city' => '%es%'
//   ]
// ])->fetchAll();


// LIMIT
//
// $query = $db->select( $table ,'*',[
//   'LIMIT' => 10
// ])->fetchAll();


// IN
//
// $query = $db->select( $table ,'*',[
//   'IN' => [
//     'city' => ['Acoz','Pietraroja','Martelange','Relegem']
//   ]
// ])->fetchAll();

// NOT IN
//
// $query = $db->select( $table ,'*',[
//   '!IN' => [
//     'city' => ['Acoz','Pietraroja','Martelange','Relegem']
//   ]
// ])->fetchAll();



// IN | BETWEEN | NOT LIKE | ORDER BY
//
// $query = $db->select( $table ,'*',[
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
//
// Delete all data (Use ->rowCount() to know how many rows have been affected)
// $query = $db->delete( $table ); 
// OR 
// $query = $db->delete( $table , '*' );


// COUNT
// 
// $query = $db->count( $table, '*', [
//   // 'LIKE' => [
//   //   'address' => '%Avenue%'
//   // ]
//   // 
//   // 'id{>}' => 275
// ]);
// 
// var_dump( $query );


// DELETE TABLE
//
// $query = $db->deleteTable( $table );


// TRUNCATE TABLE
//
// $query = $db->truncate( $table );


print_r( $query );
