<?php die('see the source of this file');


include __DIR__."/../dist/YSPDO.php";


// CUBRID
$db = new YSPDO([
  'cubrid',
  'dbname' => 'generatedata',
  'host' => '192.168.0.10',
  'port' => 30000
],'username','password');
// DSN cubrid:dbname=demodb;host=192.168.0.10;port=30000



// MS SQL Server
$db = new YSPDO([
  'mssql',
  'host' => 'sqlserver01.database.com',
  'dbname' => 'db'
],'username','password');
// DSN mssql:host=sqlserver01.database.com;dbname=db



// Firebird
$db = new YSPDO([
  'firebird',

  // PDO_FIREBIRD DSN example with path
  'dbname' => '/path/to/DATABASE.FDB',

  // or PDO_FIREBIRD DSN example with port and path
  'dbname' => 'hostname/port:/path/to/DATABASE.FDB',

  // or PDO_FIREBIRD DSN example with localhost and path to employee.fdb on Debian system
  'dbname' => 'localhost:/var/lib/firebird/2.5/data/employee.fdb'
]);



// IBM
$db = new YSPDO([
  'ibm',
  'DRIVER'    => '{IBM DB2 ODBC DRIVER}',
  'DATABASE'  => 'testdb',
  'HOSTNAME'  => '11.22.33.444',
  'PORT'      => '56789',
  'PROTOCOL'  => 'TCPIP'
],'username','password');
// DSN ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=testdb;HOSTNAME=11.22.33.444;PORT=56789;PROTOCOL=TCPIP



// INFORMIX
$db = new YSPDO([
  'informix',
  'host'                    => 'host.domain.com',
  'service'                 => '9800',
  'database'                => 'common_db',
  'server'                  => 'ids_server',
  'protocol'                => 'onsoctcp',
  'EnableScrollableCursors' => '1'
],'username','password');
// DSN informix:host=host.domain.com;service=9800;database=common_db;server=ids_server;protocol=onsoctcp;EnableScrollableCursors=1



// MySQL
$db = new YSPDO([
  'mysql',
  'host' => 'localhost',
  'dbname' => 'generatedata',
  'port' => 3306,
  'charset' => 'utf8',
],'username','password');
// DSN mysql:host=localhost;dbname=generatedata;port=3306;charset=utf8



// MS SQL Server
$db = new YSPDO([
  'sqlsrv',
  'Server'    => '12345abcde.database.windows.net',
  'Database'  => 'testdb'
],'username','password');
// DSN sqlsrv:Server=12345abcde.database.windows.net;Database=testdb



// Oracle
$db = new YSPDO([
  'oci',

  // Connect to a database defined in tnsnames.ora
  'dbname' => 'mydb',

  // or Connect using the Oracle Instant Client
  'dbname' => '//localhost:1521/mydb'

]);



// ODBC and DB2
$db = new YSPDO(['odbc','MSSQLServer'],'username','password');
// DSN odbc:MSSQLServer



// PostgreSQL
$db = new YSPDO([
  'pgsql',
  'host'      => 'localhost',
  'port'      => 5432,
  'dbname'    => 'testdb',
  'user'      => 'username',
  'password'  => 'password'
]);
// DSN pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass



// SQLite
$db = new YSPDO(['sqlite','/opt/databases/mydb.sq3']);
// OR
$db = new YSPDO(['sqlite',':memory:']);
// OR
$db = new YSPDO(['sqlite2','/opt/databases/mydb.sq2']);
// OR
$db = new YSPDO(['sqlite2',':memory:']);



// 4D
$db = new YSPDO([
  '4D',
  'host' => 'localhost',
  'charset' => 'UTF-8'
]);
// DSN 4D:host=localhost;charset=UTF-8



// Only tested on drivers MySQL and SQLite
