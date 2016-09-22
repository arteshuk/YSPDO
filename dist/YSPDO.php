<?php

/**
 * YSPDO - Manipulate table records of database with PDO using arrays
 *
 * @author  Gabriel Almeida - gabrieel@email.com
 * @version 1.0.1
 * @license MIT
 */

class YSPDO {

  /**
  * @var $dsn
  */
  private $dsn = null;

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
                        'prefix'    => '',
                        'suffix'    => ''
                      ];

  /**
  * Method magic __construct
  *
  * @param array $dsn
  * @param string $username
  * @param string $password
  * @param array $options
  * @return void
  */
  public function __construct(array $dsn, string $username = null, string $password = null, array $options = []){
    $this->settings = (object) $this->settings;
    $this->_buildDSN($dsn);
    try{
      if(!in_array($this->settings->driver,$this->getAvailableDrivers())){
        die('YSPDO error: '.strtoupper($this->settings->driver).' driver unavailable');
      }
      $this->connection = new \PDO( $this->dsn , $username, $password, $options );
    }catch (PDOException $e){
      $this->_PDOException($e);
    }
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
  * PDO::getAvailableDrivers
  *
  * @return array
  */
  public function getAvailableDrivers() : array {
    return \PDO::getAvailableDrivers();
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
  public function quote($string, $parameter_type=\PDO::PARAM_STR) : string {
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
  * @return class
  */
  public function bindColumn($column, $param, $type=\PDO::FETCH_ASSOC, $maxlen=0, $driverdata=null) : bool {
    $this->query->bindColumn($column, $param, $type, $maxlen, $driverdata);
    return $this;
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
    $this->query->bindParam($parameter, $value, $type, $length);
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
  public function fetch($style="ASSOC", $cursor_orientation=\PDO::FETCH_ORI_NEXT, $offset=0){
    return ( $this->query != null ) ? $this->query->fetch($this->_fetchStyle( $style , $cursor_orientation, $offset )) : false;
  }

  /**
  * Fetch all Statement
  *
  * @param string $style
  * @param mixed $argument
  * @param array $ctor_args
  * @return mixed
  */
  public function fetchAll($style="ASSOC", $argument=\PDO::FETCH_COLUMN, $ctor_args=[]){
    return ( $this->query != null ) ? $this->query->fetchAll($this->_fetchStyle( $style , $argument , $ctor_args )) : false;
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
  * @param array|null   $where
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
  public function update($table,$data,$where=null){
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
    $sql = "CREATE TABLE {$this->_backtick($table)} (\n\t";
    foreach ($rows as $row_k => $row_v) {
      switch ($row_k) {
        case 'PRIMARY KEY':
          $st = " PRIMARY KEY (";
          foreach ($row_v as $sv) {
            $st .= $this->_backtick($sv).',';
          }
          $sql .= rtrim($st,',').')';
        break;
        case 'KEY': case 'UNIQUE KEY':
          $sql .= ' ' . $row_k . ' ' . $this->_backtick($row_v) . ' ('.$this->_backtick($row_v).')';
        break;
        default:
        $sql .= $this->_backtick($row_k);
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
  * @param string|array|null $where
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
                    $n .= $this->_backtick( $v ).',';
                  }
                  $n = rtrim($n,',');
                }else{
                  $n .= $value;
                }
              }elseif ($key === 'AS') {
                foreach ($value as $_key => $_val) {
                  $data = [$this->_backtick( $_key ),$this->_backtick( $_val )];
                  $n .= $data[0].' AS '.$data[1].',';
                }
                $n = rtrim($n,',');
              }else{
                if(is_string( $key )){
                  $n .= ' '.$key.' '.$value.',';
                }else{
                  $n .= $this->_backtick( $value ).',';
                }
              }
            }
            $n = rtrim($n,',');
          }else{
            if(strtolower( $data ) == 'all' || $data == '*'){
              $n = '*';
            }else{
              $n = ( preg_match("/[[\s,`]+/", $data) === 1 ) ? $data : $this->_backtick( $data );
            }
          }
          $sql = 'SELECT '.$n.' FROM '.$this->_backtick( $table , false ) . $this->_cWhere( $where );
        break;
        case 'insert':
          $sql = 'INSERT INTO '.$this->_backtick( $table , false ).' ';
          $v = '';
          $n = '';
          foreach( $data as $key => $val ){
            $n .= $this->_backtick( $key ).', ';
            $v .= '?, ';
          }
          $sql .= "(". rtrim($n, ', ') .") VALUES (". rtrim($v, ', ') .");";
        break;
        case 'update':
          $sql = 'UPDATE '.$this->_backtick( $table , false ).' SET ';
          foreach( $data as $key => $val ){
              if(!empty($val)) $sql .= $this->_backtick( $key ).'=?, ';
          }
          $sql = rtrim($sql, ', ') . $this->_cWhere( $where );
        break;
        case 'delete':
          if($where === '*' || (is_string($where) && strtolower($where) == 'all')){
            $sql = 'DELETE * FROM ' . $this->_backtick( $table , false );
          }else{
            $sql = 'DELETE FROM ' . $this->_backtick( $table , false ) . $this->_cWhere( $where );
          }
        break;
      }
    return $sql;
  }


  /**
  * Add backtick||brackets on string
  *
  * @param string $s
  * @param boolean $ps
  * @return string
  */
  private function _backtick($s,$ps=true){
    if( preg_match( '/\[(.*)\]/' , $s ) == 0 ){
      if( strpos( $s , '.' ) !== false ){
        $s = explode('.',$s);
        foreach ($s as $k => $v) {
          if($ps){
            $s[$k] = '`'.$this->settings->prefix.$v.$this->settings->suffix.'`';
          }else{
            $s[$k] = '`'.$v.'`';
          }
        }
        return join('.',$s);
      }else{
        if($ps){
          return '`'.$this->settings->prefix.$s.$this->settings->suffix.'`';
        }else{
          return '`'.$s.'`';
        }
      }
    }else{
      if($ps){
        return $this->settings->prefix.$s.$this->settings->suffix;
      }else{
        return $s;
      }
    }
  }

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
                    $pos .= $this->_backtick($data[0]).' '.$data[1].', ';
                  }else{
                    $pos .= $this->_backtick($data).', ';
                  }
                }
                $pos = rtrim($pos,', ');
              }else{
                if(strpos($val, ' ') !== false){
                  $data = explode(' ',$val);
                  $pos .= $this->_backtick($data[0]).' '.$data[1];
                }else{
                  $pos .= $this->_backtick($val);
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
              $sql .= $this->_backtick(key( $val )) . $in . '(';
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
              $sql .= $this->_backtick( key( $val ) ) . $like . '\''.$val[key($val)].'\'';
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
              $sql .= $this->_backtick(key($val))." {$between} {$data[0]} AND {$data[1]}";
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
          $sql .= $this->_backtick( $key ) . $operator . '? AND ';
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
  private function _fetchStyle($type) : int {
    switch(strtoupper($type)){
      case 'ASSOC':      $a = \PDO::FETCH_ASSOC;      break;
      case 'BOTH':       $a = \PDO::FETCH_BOTH;       break;
      case 'NAMED':      $a = \PDO::FETCH_NAMED;      break;
      case 'LAZY':       $a = \PDO::FETCH_LAZY;       break;
      case 'NUM':        $a = \PDO::FETCH_NUM;        break;
      case 'OBJ':        $a = \PDO::FETCH_OBJ;        break;
      case 'BOUND':      $a = \PDO::FETCH_BOUND;      break;
      case 'COLUMN':     $a = \PDO::FETCH_COLUMN;     break;
      case 'CLASS':      $a = \PDO::FETCH_CLASS;      break;
      case 'INTO':       $a = \PDO::FETCH_INTO;       break;
      case 'FUNC':       $a = \PDO::FETCH_FUNC;       break;
      case 'GROUP':      $a = \PDO::FETCH_GROUP;      break;
      case 'UNIQUE':     $a = \PDO::FETCH_UNIQUE;     break;
      case 'KEY_PAIR':   $a = \PDO::FETCH_KEY_PAIR;   break;
      case 'CLASSTYPE':  $a = \PDO::FETCH_CLASSTYPE;  break;
      case 'SERIALIZE':  $a = \PDO::FETCH_SERIALIZE;  break;
      case 'PROPS_LATE': $a = \PDO::FETCH_PROPS_LATE; break;
    }
    return $a ?? \PDO::FETCH_ASSOC;
  }

  /**
  * Get type of var
  *
  * @param mixed $var
  * @return int
  */
  private function _getTypeVar($v) : int {
    switch (true){
      case is_string($v): $a = \PDO::PARAM_STR;  break;
      case is_int($v):    $a = \PDO::PARAM_INT;  break;
      case is_bool($v):   $a = \PDO::PARAM_BOOL; break;
      case is_null($v):   $a = \PDO::PARAM_NULL; break;
    }
    return $a ?? \PDO::PARAM_NUL;
  }

  /**
  * Build string DSN
  *
  * @param array $d
  * @return void
  */
  private function _buildDSN(array $d){
    $b = [];
    $this->settings->driver = $d[0];
    if(count($d) == 2 && is_int(array_search(end($d),$d))){
      $this->dsn = $d[0] . ':' . $d[1];
    }else{
      $this->dsn = $d[0].':';
      unset($d[0]);
      foreach ($d as $k => $v) {
        if($k == 'prefix' || $k == 'suffix'){
          $this->settings->$k = $v;
        }else{
          array_push($b, $k.'='.$v);
        }
      }
      $this->dsn .= join($b,';');
    }
  }
}
// YSPDO = ARRAYS+PDO
