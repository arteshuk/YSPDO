<?php

/**
 * YSPDO - Dynamically manipulate database with arrays
 *
 * @author  Gabriel Almeida - gabrieel@email.com
 * @version 1.0.0
 * @license MIT
 */

class YSPDO {

  /**
  * @var $connection
  */
  private $connection = null;

  /**
  * @var $query
  */
  private $query   = null;
  /**
  * @var $settings
  */
  private $settings = [
                      'driver'    => '',
                      'host'      => '',
                      'port'      => 3306,
                      'charset'   => 'utf8',
                      'database'  => '',
                      'user'      => '',
                      'password'  => ''
                      ];

  /**
  * Method magic __construct
  *
  * @param array $settings
  * @return void
  */
  public function __construct($settings=null){
    if(!is_null($settings)){
      $this->_setSettings($settings);
    }
    $this->init();
  }
  /**
  * Method magic __destruct
  *
  * @return void
  */
  public function __destruct(){
    $this->connection   = null;
    $this->query        = null;
  }

  /**
  * Initialize connection with database
  *
  * @return void
  */
  private function init(){
    try{

      if(!in_array($this->settings['driver'],$this->getAvailableDrivers())){
        die(strtoupper($this->settings['driver'])." Driver not loaded");
      }

      switch ($this->settings['driver']) {
        case 'mysql':
          $this->connection = new PDO(
            'mysql'
            .':host='.$this->settings['host']
            .';port='.$this->settings['port']
            .';dbname='.$this->settings['database']
            .';charset='.$this->settings['charset'],
            $this->settings['user'],
            $this->settings['password'],
            [
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_PERSISTENT => false
            ]
          );
        break;
        case 'mssql':
        case 'sybase':
          $this->connection = new PDO("{$this->settings['driver']}:host={$this->settings['host']};dbname={$this->settings['database']},{$this->settings['user']},{$this->settings['password']}");
        break;
        case 'sqlite':
          $this->connection = new PDO("sqlite:{$this->settings['database']}");
        break;
        case 'firebird':
          $this->connection = new PDO("firebird:host={$this->settings['host']};dbname={$this->settings['database']};charset={$this->settings['charset']}", $this->settings['user'], $this->settings['password']);
        break;
        case 'pgsql':
          $this->connection = new PDO("pgsql:host={$this->settings['host']};port={$this->settings['port']};dbname={$this->settings['database']};user={$this->settings['user']};password={$this->settings['password']}");
        break;
        default:
          die("Database driver {$this->settings['driver']} unavailable");
          break;
      }
    }catch (PDOException $e){
      $this->_PDOException($e);
    }
  }

  /**
  * PDO::getAvailableDrivers
  *
  * @return array
  */
  public function getAvailableDrivers() : array {
    return PDO::getAvailableDrivers();
  }

  /**
  * PDO::commit
  *
  * @return boolean
  */
  public function commit() : bool {
    return $this->connection->commit();
  }

  /**
  * PDO::beginTransaction
  *
  * @return boolean
  */
  public function beginTransaction() : bool {
    return $this->connection->beginTransaction();
  }

  /**
  * PDO::rollBack
  *
  * @return boolean
  */
  public function rollBack() : bool {
    return $this->connection->rollBack();
  }

  /**
  * PDO::inTransaction
  *
  * @return boolean
  */
  public function inTransaction() : bool {
    return $this->connection->inTransaction();
  }

  /**
  * PDO::exec
  *
  * @param string $statement
  * @return integer|boolean
  */
  public function exec($statement) {
    return $this->connection->exec( $statement );
  }

  /**
  * PDO::quote
  *
  * @param string $string
  * @param int $parameter_type
  * @return string
  */
  public function quote($string, $parameter_type=PDO::PARAM_STR) : string {
    return $this->connection->quote( $string , $parameter_type );
  }

  /**
  * PDO::errorCode
  *
  * @return mixed
  */
  public function errorCode() {
    return $this->connection->errorCode();
  }

  /**
  * PDO::errorInfo
  *
  * @return array
  */
  public function errorInfo() : array {
    return $this->connection->errorInfo();
  }

  /**
  * Query Statement
  *
  * @param string $sql
  * @param array [optional] $parameters
  * @return array
  */
  public function query($sql, $parameters=null){
    try {
      $this->query = $this->connection->prepare($sql);
      return $this->execute($parameters);
    }catch(PDOException $e) {
      $this->_PDOException($e);
    }
  }


  /**
  * Row count
  *
  * @return integer
  */
  public function rowCount() : int {
    return $this->query->rowCount();
  }

  /**
  * Last insert id
  *
  * @return integer
  */
  public function lastInsertId() : int {
    return $this->connection->lastInsertId();
  }

  /**
  * Prepare Statement
  *
  * @param string $sql
  * @return class
  */
  public function prepare($sql){
    $this->query = $this->connection->prepare( $sql );
    return $this;
  }

  /**
  * bindColumn Statement
  *
  * @param mixed $column
  * @param mixed $param
  * @param int $type
  * @param int $maxlen
  * @param mixed $driverdata
  * @return boolean
  */
  public function bindColumn($column, $param, $type=PDO::FETCH_ASSOC, $maxlen=0, $driverdata=null) : bool {
    return $this->query->bindColumn($column, $param, $type, $maxlen, $driverdata);
  }

  /**
  * bindParam Statement
  *
  * @param mixed $parameter
  * @param mixed $value
  * @param int $type
  * @param int $length
  * @return class
  */
  public function bindParam($parameter,$value,$type=null,$length=null){
    $this->query->bindParam($parameter,$value,$type,$length);
    return $this;
  }

  /**
  * bindValue Statement
  *
  * @param mixed $parameter
  * @param mixed $value
  * @param int $type
  * @return class
  */
  public function bindValue($parameter,$value,$type=null){
    $this->query->bindValue($parameter,$value,$type);
    return $this;
  }

  /**
  * closeCursor Statement
  *
  * @return boolean
  */
  public function closeCursor() : bool {
    return $this->query->closeCursor();
  }

  /**
  * columnCount Statement
  *
  * @return int
  */
  public function columnCount() : int {
    return $this->query->columnCount();
  }

  /**
  * Execure Statement
  *
  * @param array $parameters
  * @return boolean
  */
  public function execute($parameters=null) : bool {
    return $this->query->execute($parameters);
  }

  /**
  * Fetch Statement
  *
  * @param string $style
  * @param int $cursor_orientation
  * @param int $offset
  * @return mixed
  */
  public function fetch($style="ASSOC", $cursor_orientation=PDO::FETCH_ORI_NEXT, $offset=0){
    return ( $this->query != null ) ? $this->query->fetch($this->_fetchType( $style , $cursor_orientation, $offset )) : false;
  }

  /**
  * Fetch all Statement
  *
  * @param string $style
  * @param mixed $argument
  * @param array $ctor_args
  * @return mixed
  */
  public function fetchAll($style="ASSOC", $argument=PDO::FETCH_COLUMN, $ctor_args=[]){
    return ( $this->query != null ) ? $this->query->fetchAll($this->_fetchType( $style , $argument , $ctor_args )) : false;
  }

  /**
  * Fetch column Statement
  *
  * @param int $column_number
  * @return midex
  */
  public function fetchColumn($column_number=0){
    return ( $this->query != null ) ? $this->query->fetchColumn( $column_number ) : false;
  }

  /**
  * Fetch object Statement
  *
  * @param string $class_name
  * @param array $ctor_args
  * @return midex
  */
  public function fetchObject($class_name="stdClass",$ctor_args=[]){
    return ( $this->query != null ) ? $this->query->fetchObject( $class_name , $ctor_args ) : false;
  }

  /**
  * getAttribute Statement
  *
  * @param int $attribute
  * @return mixed
  */
  public function getAttribute($attribute){
    return $this->query->getAttribute( $attribute );
  }

  /**
  * setAttribute Statement
  *
  * @param int $attribute
  * @param mixed $value
  * @return boolean
  */
  public function setAttribute($attribute, $value) : bool {
    return $this->query->setAttribute( $attribute , $value );
  }

  /**
  * Select Statement
  *
  * @param string       $table
  * @param string|array $columns
  * @param array        $where
  * @return class
  */
  public function select($table,$columns='*',$where=null){
    $sql = $this->cSQL('select', $table, $columns, $where);
    try {
      $this->query = $this->connection->prepare( $sql );
      if(is_array($where)){
        $i=1;
        foreach ($where as $key => $val){
          if(!in_array($key,['ORDER','LIMIT','IN','!IN','LIKE','!LIKE','BETWEEN','!BETWEEN'])){
            $this->query->bindValue($i, $val, $this->_getTypeVar( $val ));
            ++$i;
          }
        }
      }
      $this->execute();
    }catch(PDOException $e) {
      $this->_PDOException($e);
    }
    return $this;
  }

  /**
  * Insert Statement
  *
  * @param string $table
  * @param array  $data
  * @return class
  */
  public function insert($table,$data){
    $sql = $this->cSQL('insert',$table,$data,null);
    try {
      $this->query = $this->connection->prepare($sql);
      $i=1;
      foreach($data as $key => $val) {
        $this->query->bindValue($i, $val, $this->_getTypeVar($val));
        ++$i;
      }
      $this->execute();
    }catch(PDOException $e) {
      $this->_PDOException($e);
    }
    return $this;
  }

  /**
  * Update Statement
  *
  * @param string $table
  * @param array  $data
  * @param array  $where
  * @return class
  */
  public function update($table,$data,$where){
    $sql = $this->cSQL('update',$table,$data,$where);
    try {
      $this->query = $this->connection->prepare($sql);
      $i=1;
      foreach( $data as $key => $val){
        if(!empty($val)){
            $this->query->bindValue($i, $val, $this->_getTypeVar($val));
            ++$i;
        }
      }
      if(is_array($where)){
        foreach( $where as $key => $val ) {
          $this->query->bindValue($i, $val, $this->_getTypeVar($val));
          ++$i;
        }
      }
      $this->execute();
    }catch(PDOException $e) {
      $this->_PDOException($e);
    }
    return $this;
  }

  /**
  * Delete Statement
  *
  * @param string $table
  * @param array  $where
  * @return class
  */
  public function delete($table,$where){
    $sql = $this->cSQL('delete',$table,null,$where);
    try {
      $this->query = $this->connection->prepare($sql);
      $i=1;
      foreach($where as $key => $val) {
        $this->query->bindValue($i, $val, $this->_getTypeVar($val));
        ++$i;
      }
      $this->execute();
    }catch(PDOException $e) {
      $this->_PDOException($e);
    }
    return $this;
  }

  /**
  * Create Database
  *
  * @param string $s Database name
  * @return boolean
  */
  public function createDB(string $s) : bool {
    return $this->query("CREATE DATABASE {$s};");
  }

  /**
  * Drop Database
  *
  * @param string $s Database name
  * @return boolean
  */
  public function deleteDB(string $s) : bool {
    return $this->query("DROP DATABASE {$s};");
  }

  /**
  * Create Table
  *
  * @param string $table
  * @param array $rows
  * @param array $settings
  * @return boolean
  */
  public function createTable($table,$rows,$settings){
    $sql = "CREATE TABLE {$this->_addGraveAccent($table)} (\n\t";
    foreach ($rows as $row_k => $row_v) {
      switch ($row_k) {
        case 'PRIMARY KEY':
          $st = " PRIMARY KEY (";
          foreach ($row_v as $sv) {
            $st .= $this->_addGraveAccent($sv).',';
          }
          $sql .= rtrim($st,',').')';
        break;
        case 'KEY': case 'UNIQUE KEY':
          $sql .= ' ' . $row_k . ' ' . $this->_addGraveAccent($row_v) . ' ('.$this->_addGraveAccent($row_v).')';
        break;
        default:
        $sql .= $this->_addGraveAccent($row_k);
        foreach ($row_v as $k => $v) {
          if(is_numeric($k)){
            if($k == 0){
              if($v != null){
                $sql .= ' ' . $v;
              }
            }elseif ($k == 1) {
              if($v != null){
                $sql .= '('.$v.')';
              }
            }else{
              switch ($v) {
                case 'NULL': case '!NULL':
                  $sql .= ( substr( $v , 0 , 1 ) == '!' ) ? ' NOT NULL' : ' DEFAULT NULL';
                break;
                case 'unsigned': case 'AUTO_INCREMENT':
                  $sql .= ' '.$v;
                break;
              }
            }
          }else{
            switch ($k) {
              case 'DEFAULT':
                if(in_array($v,['CURRENT_TIMESTAMP'])){
                  $sql .= ' DEFAULT '.$v;
                }else{
                  $sql .= ' DEFAULT \''.$v.'\'';
                }
              break;
              case 'COLLATE':
              case 'CHARACTER SET':
                $sql .= ' '.$k.' '.$v;
              break;
              case 'COMMENT':
                $sql .= ' COMMENT \''.$v.'\'';
              break;
              case 'set':
              case 'enum':
                $st = " {$k}(";
                foreach ($v as $sv) {
                  $st .= '\''.$sv.'\',';
                }
                $sql .= rtrim($st,',').')';
              break;
            }
          }
        }
        break;
      }
      $sql .= ",\n\t";
    }
    $sql = rtrim($sql,",\n\t");
    $sql .= "\n)";
    foreach ($settings as $k => $v) {
      switch ( $k ) {
        case 'ENGINE': case 'COLLATE': case 'DEFAULT CHARSE': case 'STATS_PERSISTENT': case 'AUTO_INCREMENT':
          $sql .= ' '.$k.'='.$v;
        break;
        case 'COMMENT':
          $sql .= ' COMMENT=\''.$v.'\'';
        break;
      }
    }
    $sql .= ';';
    try {
      return $this->exec( $sql );
    }catch(PDOException $e) {
      $this->_PDOException($e);
    }
  }

  /**
  * Drop Table
  *
  * @param string $s Table name
  * @return boolean
  */
  public function deleteTable(string $s){
    return $this->query("DROP TABLE {$s};");
  }

  /**
  * Truncate Table
  *
  * @param string $s Table name
  * @return boolean
  */
  public function truncate(string $s){
    return $this->query("TRUNCATE TABLE {$s};");
  }

  /**
  * cSQL - Create SQL Statement
  *
  * @param string $method
  * @param string $table
  * @param string|array $data
  * @param array $where
  * @return string
  */
  private function cSQL($method,$table,$data,$where){
      $sql = '';
      switch($method){
        case 'select':
          $n = '';
          if(is_array($data)){
            foreach($data as $key => $value){
              if($key === 'DISTINCT'){
                $n .= 'DISTINCT ';
                if(is_array($value)){
                  foreach ($value as $v) {
                    $n .= $this->_addGraveAccent( $v ).',';
                  }
                  $n = rtrim($n,',');
                }else{
                  $n .= $value;
                }
              }elseif ($key === 'AS') {
                foreach ($value as $_key => $_val) {
                  $data = [$this->_addGraveAccent( $_key ),$this->_addGraveAccent( $_val )];
                  $n .= $data[0].' AS '.$data[1].',';
                }
                $n = rtrim($n,',');
              }else{
                if(is_string( $key )){
                  $n .= ' '.$key.' '.$value.',';
                }else{
                  $n .= $this->_addGraveAccent( $value ).',';
                }
              }
            }
            $n = rtrim($n,',');
          }else{
            if(strtolower( $data ) == 'all' || $data == '*'){
              $n = '*';
            }else{
              $n = ( preg_match("/\s/m", $data) == 0 ) ? $this->_addGraveAccent( $data ) : $data;
            }
          }
          $sql = 'SELECT '.$n.' FROM '.$this->_addGraveAccent( $table ) . $this->_cWhere( $where );
        break;
        case 'insert':
          $sql = 'INSERT INTO '.$this->_addGraveAccent( $table ).' ';
          $v = '';
          $n = '';
          foreach( $data as $key => $val ){
            $n .= $this->_addGraveAccent( $key ).', ';
            $v .= '?, ';
          }
          $sql .= "(". rtrim($n, ', ') .") VALUES (". rtrim($v, ', ') .");";
        break;
        case 'update':
          $sql = 'UPDATE '.$this->_addGraveAccent( $table ).' SET ';
          foreach( $data as $key => $val ){
              if(!empty($val)) $sql .= $this->_addGraveAccent( $key ).'=?, ';
          }
          $sql = rtrim($sql, ', ') . ' WHERE ';
          foreach( $where as $key => $val ){
              if(!empty($val)) $sql .= $this->_addGraveAccent( $key ).'=? AND ';
          }
          $sql = rtrim($sql, ' AND ') . ';';
        break;
        case 'delete':
          $sql = 'DELETE FROM '.$this->_addGraveAccent( $table );
          if(!is_null($where)){
              $sql .= ' WHERE ';
              foreach( $where as $key => $val ){
                if(!empty($val)) $sql .= $this->_addGraveAccent( $key ).'=? AND ';
              }
          }
          $sql = rtrim($sql,' AND ') . ';';
        break;
      }
    return $sql;
  }

  /**
  * Add grave accent on string
  *
  * @param string $str
  * @return string
  */
  private function _addGraveAccent($str){ return '`'.$str.'`'; }

  /**
  * Create Where for SQL
  *
  * @param string|array $where
  * @return string
  */
  private function _cWhere($where){
    if(!is_null($where)){
      $sql = '';
      $arrayWhere = [];
      $addedWHERE = false;
      $doNotNeedAND = ['ORDER','LIMIT'];
      $pos = '';
      if(is_array($where)){
        foreach( $where as $key => $val ){
          switch ($key) {
            case 'ORDER':
              $pos .= ' ORDER BY ';
              if(is_array($val)){
                foreach ($val as $data) {
                  if(strpos($data, ' ') !== false){
                    $data = explode(' ',$data);
                    $pos .= $this->_addGraveAccent($data[0]).' '.$data[1].', ';
                  }else{
                    $pos .= $this->_addGraveAccent($data).', ';
                  }
                }
                $pos = rtrim($pos,', ');
              }else{
                if(strpos($val, ' ') !== false){
                  $data = explode(' ',$val);
                  $pos .= $this->_addGraveAccent($data[0]).' '.$data[1];
                }else{
                  $pos .= $this->_addGraveAccent($val);
                }
              }
            break;
            case 'LIMIT':
              $pos .= ' LIMIT '.$val;
            break;
            case '!IN':
            case 'IN':
              if(!$addedWHERE){
                $sql .= ' WHERE ';
                $addedWHERE = true;
              }
              $in = ( strpos( $key , '!' ) !== false ) ? ' NOT IN ' : ' IN ';
              $sql .= $this->_addGraveAccent(key( $val )) . $in . '(';
              foreach($val[key($val)] as $values){
                $sql .= '\''.$values.'\',';
              }
              $sql = rtrim($sql,',');
              $sql .= (!next($where)) ? ')' : (in_array(key($where),$doNotNeedAND)) ? ')' : ') AND ';
            break;
            case 'LIKE':
            case '!LIKE':
              if(!$addedWHERE){
                $sql .= ' WHERE ';
                $addedWHERE = true;
              }
              $like = ( strpos( $key , '!' ) !== false ) ? ' NOT LIKE ' : ' LIKE ';
              $sql .= $this->_addGraveAccent( key( $val ) ) . $like . '\''.$val[key($val)].'\'';
              if(next($where)!==false){
                if(!current($where) || !in_array(key($where),$doNotNeedAND)){
                  $sql .= ' AND ';
                }
              }
            break;
            case '!BETWEEN':
            case 'BETWEEN':
              if(!$addedWHERE){
                $sql .= ' WHERE ';
                $addedWHERE = true;
              }
              $data = $val[key($val)];
              if(gettype($data[0]) == 'string' ){
                if(!preg_match("/\W/", $data[0])){
                  $data = ['\''.$data[0].'\'','\''.$data[1].'\''];
                }
              }
              $between = ( strpos( $key , '!' ) !== false ) ? 'NOT BETWEEN' : 'BETWEEN';
              $sql .= $this->_addGraveAccent(key($val))." {$between} {$data[0]} AND {$data[1]}";
              if(next($where)!==false){
                if(!current($where) || !in_array(key($where),$doNotNeedAND)){
                  $sql .= ' AND ';
                }
              }
            break;
            default:
              if(!$addedWHERE){
                $sql .= ' WHERE ';
                $addedWHERE = true;
              }
              array_push($arrayWhere, $key);
            break;
          }
        }
        foreach ($arrayWhere as $value) {
          $key = $value;
          $operator;
          $allowedOperators = ['=','>','<','>=','<=','<>'];
          if(preg_match("/\{.*\}/", $value, $operator)){
            $key = preg_replace("/\{.*\}/", "", $value);
            $operator = str_replace(['{','}'],'',$operator[0]);
            if(!in_array($operator,$allowedOperators)){
              die("Operator ({$operator}) in Query is invalid");
            }
          }else{
            $operator = '=';
          }
          $sql .= $this->_addGraveAccent($key) . $operator . '? AND ';
        }
        $sql = rtrim($sql,' AND ');
        $sql .= $pos.';';
      }else{
        $sql .= ' '.$where.';';
      }
    }else{
      $sql = ';';
    }
    return $sql;
  }

  /**
  * Get Message Exception
  *
  * @param object $e
  * @return string
  */
  private function _PDOException($e){
    $this->query = null;
    die($e->getMessage());
  }

  /**
  * Get type for fetch
  *
  * @param string type
  * @return int
  */
  private function _fetchType($type) : int {
    switch(strtoupper($type)){
      case 'ASSOC':      $a = PDO::FETCH_ASSOC;      break;
      case 'BOTH':       $a = PDO::FETCH_BOTH;       break;
      case 'NAMED':      $a = PDO::FETCH_NAMED;      break;
      case 'LAZY':       $a = PDO::FETCH_LAZY;       break;
      case 'NUM':        $a = PDO::FETCH_NUM;        break;
      case 'OBJ':        $a = PDO::FETCH_OBJ;        break;
      case 'BOUND':      $a = PDO::FETCH_BOUND;      break;
      case 'COLUMN':     $a = PDO::FETCH_COLUMN;     break;
      case 'CLASS':      $a = PDO::FETCH_CLASS;      break;
      case 'INTO':       $a = PDO::FETCH_INTO;       break;
      case 'FUNC':       $a = PDO::FETCH_FUNC;       break;
      case 'GROUP':      $a = PDO::FETCH_GROUP;      break;
      case 'UNIQUE':     $a = PDO::FETCH_UNIQUE;     break;
      case 'KEY_PAIR':   $a = PDO::FETCH_KEY_PAIR;   break;
      case 'CLASSTYPE':  $a = PDO::FETCH_CLASSTYPE;  break;
      case 'SERIALIZE':  $a = PDO::FETCH_SERIALIZE;  break;
      case 'PROPS_LATE': $a = PDO::FETCH_PROPS_LATE; break;
      default:
        $a = PDO::FETCH_ASSOC;
      break;
    }
    return $a;
  }

  /**
  * Get type of var
  *
  * @param mixed $var
  * @return int
  */
  private function _getTypeVar($v) : int {
    switch (true){
      case is_string($v): $a = PDO::PARAM_STR;  break;
      case is_int($v):    $a = PDO::PARAM_INT;  break;
      case is_bool($v):   $a = PDO::PARAM_BOOL; break;
      case is_null($v):   $a = PDO::PARAM_NULL; break;
    }
    return $a ?? 0;
  }

  /**
  * Set settings
  *
  * @param array $settings
  * @return void
  */
  private function _setSettings($settings){
    foreach ($settings as $key => $value) {
      if(array_key_exists($key,$this->settings)){
        $this->settings[$key] = $value;
      }
    }
  }
}
// YSPDO = ARRAYS+PDO
