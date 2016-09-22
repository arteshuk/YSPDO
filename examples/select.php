<?php

include __DIR__ . "/../dist/YSPDO.php";


$db = new YSPDO([
  'mysql',
  'host' => 'localhost',
  'port' => 3306,
  'dbname' => 'generatedata',
  'charset' => 'utf8',
],'root','');


/**
* YSPDO->select( $table, $columns, $where )
*
  * @param string       $table
  * @param string|array $columns  [optional]
  * @param array        $where    [optional]
  * @return object [class YSPDO]
*
*/


/**
   Remove the comments and enjoy it
**/


// Column
//
// $query = $db->select('peoples',['name']);
// Or without array (recommended)
// $query = $db->select('peoples','name');
//
// SQL Statement: SELECT `name` FROM `peoples`;


// Columns
//
// $query = $db->select('peoples',['name','email','address','company']);
// Or without array
// $query = $db->select('peoples','`name`,`email`,`address`,`company`');
//
// SQL Statement: SELECT `name`,`email`,`address`,`company` FROM `peoples`;


// DISTINCT
//
// $query = $db->select( 'peoples' ,[ 'DISTINCT' => '`name`' ]);
// Or using array
// $query = $db->select( 'peoples' ,[ 'DISTINCT' => ['name','phone','date'] ]);
// Without array
// $query = $db->select( 'peoples' , 'DISTINCT `name`,`phone`,`date`');
//
// SQL Statement: SELECT DISTINCT `name`,`phone`,`date` FROM `peoples`;


// ALIASES
//
// $query = $db->select( 'peoples' ,[
//   'AS' => [
//     'name' => 'yourName',
//     'date' => 'birthday',
//     'email' => 'contact'
//   ]
// ]);
// Without array
// $query = $db->select( 'peoples' , '`name` AS `yourName`,`date` AS `birthday`,`email` AS `contact`');
//
// SQL Statement: SELECT `name` AS `yourName`,`date` AS `birthday`,`email` AS `contact` FROM `peoples`;


// COMPARISON OPERATORS
//
// $query = $db->select( 'peoples' ,'*', [ 'id{>=}' => 10, 'id{<=}' => 50 ]);
// Without array
// $query = $db->select( 'peoples' ,'*', 'WHERE id>=10 AND id<=50');
//
// SQL Statement (with array): SELECT * FROM `peoples` WHERE id>=? AND id<=?;
// SQL Statement (without array): SELECT * FROM `peoples` WHERE id>=10 AND id<=50;


// ORDER BY
//
// $query = $db->select( 'peoples' ,'all',[
//   // 'ORDER' => 'city'
//   // OR
//   // 'ORDER' => 'name DESC'
//   // OR
//   'ORDER' => ['name DESC','city ASC']
// ]);
// Without array
// $query = $db->select( 'peoples' ,'all', 'ORDER BY `name` DESC, `city` ASC');
//
// SQL Statement: SELECT * FROM `peoples` ORDER BY `name` DESC, `city` ASC;


// BETWEEN
//
// $query = $db->select( 'peoples' ,'all',[
//   'BETWEEN' => [
//     'id' => [1,275] // accepts only array with two values being only string or number
//   ]
// ]);
// Without array
// $query = $db->select( 'peoples' , 'all', 'WHERE `id` BETWEEN 1 AND 275');
//
// SQL Statement: SELECT * FROM `peoples` WHERE `id` BETWEEN 1 AND 275;


// NOT BETWEEN
//
// $query = $db->select( 'peoples' ,'all',[
//   '!BETWEEN' => [
//     'name' => ['a','d'] // accepts only array with two values being only string or number
//   ]
// ]);
// Without array
// $query = $db->select( 'peoples' , 'all', 'WHERE `name` NOT BETWEEN \'a\' AND \'d\'');
//
// SQL Statement: SELECT * FROM `peoples` WHERE `name` NOT BETWEEN 'a' AND 'd';


// NOT LIKE
//
// $query = $db->select( 'peoples' ,'all',[
//   '!LIKE' => [
//     'name' => 'c%'
//   ]
// ]);
// Without array
// $query = $db->select( 'peoples' , 'all', 'WHERE `name` NOT LIKE \'c%\'');
//
// SQL Statement: SELECT * FROM `peoples` WHERE `name` NOT LIKE 'c%';


// LIKE
//
// $query = $db->select( 'peoples' ,'*',[
//   'LIKE' => [
//     'city' => '%es%'
//   ]
// ]);
// Without array
// $query = $db->select( 'peoples' , 'all', 'WHERE `city` LIKE \'%es%\'');
//
// SQL Statement: SELECT * FROM `peoples` WHERE `city` LIKE '%es%';


// LIMIT
//
// $query = $db->select( 'peoples' ,'ALL', [ 'LIMIT' => 10 ]);
// Without array
// $query = $db->select( 'peoples' ,'ALL', 'LIMIT 10');
//
// SQL Statement: SELECT * FROM `peoples` LIMIT 10;


// IN
//
// $query = $db->select( 'peoples' ,'ALL',[
//   'IN' => [
//     'city' => ['Acoz','Pietraroja','Martelange','Relegem']
//   ]
// ]);
// Without array
// $query = $db->select( 'peoples' ,'ALL', 'WHERE `city` IN (\'Acoz\',\'Pietraroja\',\'Martelange\',\'Relegem\')');
//
// SQL Statement: SELECT * FROM `peoples` WHERE `city` IN ('Acoz','Pietraroja','Martelange','Relegem');


// NOT IN
//
// $query = $db->select( 'peoples' ,'all',[
//   '!IN' => [
//     'city' => ['Acoz','Pietraroja','Martelange','Relegem']
//   ]
// ]);
// Without array
// $query = $db->select( 'peoples' ,'all', 'WHERE `city` NOT IN (\'Acoz\',\'Pietraroja\',\'Martelange\',\'Relegem\')');
//
// SQL Statement: SELECT * FROM `peoples` WHERE `city` NOT IN ('Acoz','Pietraroja','Martelange','Relegem');


print_r( $query->fetchAll() );
