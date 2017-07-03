<?php

/**
 * YSPDO - Manipulate table records of database with PDO using arrays
 *
 * @author  Gabriel Almeida - gabrieel@email.com
 * @version 1.1.0
 * @license MIT
 */

class YSPDO {

  /**
  * @var $driver
  */
  private $driver = '';

  /**
  * @var $dsn
  */
  private $dsn = null;

  /**
  * @var $conn
  */
  private $conn = null;

  /**
  * @var $query
  */
  private $query   = null;
  
  /**
  * @var $rw_operators
  */
  private $rw_operators = ['ORDER','LIMIT','IN','!IN',' LIKE','!LIKE','BETWEEN','!BETWEEN'];


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
    $this->buildDSN($dsn);
    try{
      if(!in_array($this->driver,$this->getAvailableDrivers())){
        die('YSPDO error: '.strtoupper($this->driver).' driver unavailable');
      }
      $this->conn = new \PDO( $this->dsn , $username, $password, $options );
    }catch (PDOException $e){
      $this->err_exception([0,(int) $e->getCode(),$e->getMessage()]);
    }
  }

  /**
  * Method magic __destruct
  *
  * @return void
  */
  public function __destruct(){
    $this->conn   = null;
    $this->query  = null;
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
    return $this->conn->commit();
  }

  /**
  * PDO::beginTransaction
  *
  * @return boolean
  */
  public function beginTransaction() : bool {
    return $this->conn->beginTransaction();
  }

  /**
  * PDO::rollBack
  *
  * @return boolean
  */
  public function rollBack() : bool {
    return $this->conn->rollBack();
  }

  /**
  * PDO::inTransaction
  *
  * @return boolean
  */
  public function inTransaction() : bool {
    return $this->conn->inTransaction();
  }

  /**
  * PDO::exec
  *
  * @param string $statement
  * @return integer|boolean
  */
  public function exec(string $statement) {
    return $this->conn->exec( $statement );
  }

  /**
  * PDO::quote
  *
  * @param string $string
  * @param int $parameter_type
  * @return string
  */
  public function quote($string, $parameter_type=\PDO::PARAM_STR) : string {
    return $this->conn->quote( $string , $parameter_type );
  }

  /**
  * PDO::errorCode
  *
  * @return mixed
  */
  public function errorCode() {
    return $this->query->errorCode();
  }

  /**
  * PDO::errorInfo
  *
  * @return array
  */
  public function errorInfo() : array {
    return $this->query->errorInfo();
  }

  /**
  * Query Statement
  *
  * @param string $sql
  * @param array $parameters
  * @return YSPDO
  */
  public function query(string $sql, array $parameters=[]){
    $this->query = $this->conn->prepare($sql);
    if(!$this->execute($parameters)) $this->err_exception();      
    return $this;
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
    return $this->conn->lastInsertId();
  }

  /**
  * Prepare Statement
  *
  * @param string $sql
  * @return YSPDO
  */
  public function prepare(string $sql){
    $this->query = $this->conn->prepare( $sql );
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
  * @return YSPDO
  */
  public function bindColumn($column, $param, $type=\PDO::FETCH_ASSOC, $maxlen=0, $driverdata=null) {
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
  * @return YSPDO
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
  * @return YSPDO
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
  * execure Statement
  *
  * @param array $parameters
  * @return boolean
  */
  public function execute(array $parameters=[]) : bool {
    return $this->query->execute( ( ( count( $parameters ) > 0 ) ? $parameters : null ) );
  }

  /**
  * fetch Statement
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
  * fetchAll Statement
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
  * fetchColumn Statement
  *
  * @param int $column_number
  * @return midex
  */
  public function fetchColumn(int $column_number = 0){
    return ( $this->query != null ) ? $this->query->fetchColumn( $column_number ) : false;
  }

  /**
  * fetchObject Statement
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
  public function getAttribute(int $attribute){
    return $this->query->getAttribute( $attribute );
  }

  /**
  * setAttribute Statement
  *
  * @param int $attribute
  * @param mixed $value
  * @return boolean
  */
  public function setAttribute(int $attribute, $value) : bool {
    return $this->query->setAttribute( $attribute , $value );
  }

  /**
  * SELECT
  *
  * @param string       $table
  * @param string|array $columns
  * @param array|null   $where
  * @return YSPDO
  */
  public function select(string $table,$columns='*',$where=null){
    $sql = $this->cSQL('select', $table, $columns, $where);
    $this->query = $this->conn->prepare( $sql );
    if(is_array($where)){
      $i=1;
      foreach ($where as $key => $val){
        if(!in_array($key, $this->rw_operators )){
          $this->query->bindValue($i, $val, $this->_getTypeVar( $val ));
          ++$i;
        }
      }
    }
    if(!$this->execute()) $this->err_exception();      
    return $this;
  }

  /**
  * INSERT
  *
  * @param string $table
  * @param array  $data
  * @return YSPDO
  */
  public function insert(string $table, array $data){
    $sql = $this->cSQL('insert',$table,$data,null);
    $this->query = $this->conn->prepare($sql);
    $i=1;
    foreach($data as $key => $val) {
      $this->query->bindValue($i, $val, $this->_getTypeVar($val));
      ++$i;
    }
    
    if(!$this->execute()) $this->err_exception();
    
    return $this;
  }

  /**
  * UPDATE
  *
  * @param string $table
  * @param array  $data
  * @param array|null  $where
  * @return YSPDO
  */
  public function update(string $table, array $data, $where=null){
    $sql = $this->cSQL('update',$table,$data,$where);
    $this->query = $this->conn->prepare($sql);
    $i=1;
    foreach( $data as $key => $val){
      $this->query->bindValue($i, $val, $this->_getTypeVar($val));
      ++$i;
    }
    if(is_array($where)){
      foreach( $where as $key => $val ) {
        $this->query->bindValue($i, $val, $this->_getTypeVar($val));
        ++$i;
      }
    }
    if(!$this->execute()) $this->err_exception();
    return $this;
  }

  /**
  * DELETE
  *
  * @param string $table
  * @param string|array  $where
  * @return YSPDO
  */
  public function delete(string $table, $where=''){
    $sql = $this->cSQL('delete',$table,null,$where);
    $this->query = $this->conn->prepare($sql);
    if(is_array($where) && count($where) > 0){
      $i=1;
      foreach($where as $key => $val) {
        $this->query->bindValue($i, $val, $this->_getTypeVar($val));
        ++$i;
      }
    }
    if(!$this->execute()) $this->err_exception();      
    return $this;
  }

  /**
  *  COUNT
  *
  * @param string $table
  * @param string|array $columns
  * @param array $where
  * @return string
  */
  public function count(string $table, $columns = '*', array $where = []){
    $sql = $this->cSQL('count',$table,$columns,$where);
    $this->query = $this->conn->prepare($sql);    
    if(count($where) > 0){
      $i=1;
      foreach ($where as $key => $val){
        if(!in_array($key, $this->rw_operators )){
          $this->query->bindValue($i, $val, $this->_getTypeVar( $val ));
          ++$i;
        }
      }
      if(!$this->execute()) $this->err_exception();      
      return $this->fetchColumn();
    }else{
      return $this->query( $sql )->fetchColumn();
    }
  }

  /**
  * Create Database
  *
  * @param string $s Database name
  * @return boolean
  */
  public function createDB(string $s) : bool {
    return ($this->conn->query("CREATE DATABASE {$s};") !== false ) ? true : false;
  }

  /**
  * Drop Database
  *
  * @param string $s Database name
  * @return boolean
  */
  public function deleteDB(string $s) : bool {
    return ($this->conn->query("DROP DATABASE {$s};") !== false ) ? true : false;
  }

  /**
  * Drop Table
  *
  * @param string $s Table name
  * @return boolean
  */
  public function deleteTable(string $s) : bool {
    return ($this->conn->query("DROP TABLE {$s};") !== false ) ? true : false;
  }

  /**
  * Truncate Table
  *
  * @param string $s Table name
  * @return boolean
  */
  public function truncate(string $s) : bool {
    return ($this->conn->query("TRUNCATE TABLE {$s};") !== false ) ? true : false;
  }

  /**
  * Create SQL Statement
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
                    $n .= $this->backtick( $v ).',';
                  }
                  $n = rtrim($n,',');
                }else{
                  $n .= $value;
                }
              }elseif ($key === 'AS') {
                foreach ($value as $_key => $_val) {
                  $data = [$this->backtick( $_key ),$this->backtick( $_val )];
                  $n .= $data[0].' AS '.$data[1].',';
                }
                $n = rtrim($n,',');
              }else{
                if(is_string( $key )){
                  $n .= ' '.$key.' '.$value.',';
                }else{
                  $n .= $this->backtick( $value ).',';
                }
              }
            }
            $n = rtrim($n,',');
          }else{
            if($data != '*'){
              $n = ( preg_match("/[[\s,`]/", $data) === 1 ) ? $data : $this->backtick( $data );
            }else{
              $n = '*';
            }
          }
          $sql = 'SELECT '.$n.' FROM '.$this->backtick( $table ) . $this->cWHERE( $where );
        break;
        case 'insert':
          $keys = join(',',array_map(function($n){ return $this->backtick( $n ); }, array_keys($data)));
          $qo = rtrim(str_repeat('?,',count($data)), ',');
          $sql = "INSERT INTO {$this->backtick( $table )} ({$keys}) VALUES ({$qo});";
          unset($keys,$qo);
        break;
        case 'update':
          $data = join(',',array_map(function($n){ return $this->backtick( $n ) . '=?'; }, array_keys($data)));
          $sql = "UPDATE {$this->backtick( $table )} SET {$data} " . $this->cWHERE( $where );
        break;
        case 'delete':
          $sql = "DELETE" . ((($where == '*') ? ' * ' : ' ' )) . "FROM {$this->backtick( $table )}"  . ((is_array($where) && count($where) > 0) ? ' ' . $this->cWHERE( $where ) : ';');
        break;
        case 'count':
          if(is_array($data)){
            $data = join(',', array_map(function($n){ return $this->backtick($n); },$data));
          }else{
            $data = ($data=='*') ? $data : $this->backtick($data);
          }
          $sql = "SELECT COUNT({$data}) FROM {$this->backtick($table)}" . ((count($where) > 0) ? ' ' . $this->cWHERE( $where ) : ';');
        break;
      }
    unset($method,$table,$data,$where);
    return $sql;
  }

  /**
  * Create Where for SQL
  *
  * @param string|array $where
  * @return string
  */
  private function cWHERE($where){

    $pattern_comparison_operators = '/\{(=|!=|<>|>|<|>=|<=|!<|!>)\}/';

    if (is_array($where) && count($where) > 0) {
      $queryArray = ['WHERE'];
      foreach($where as $whereKey => $whereValue) {
        $lastKeyQueryArray = count($queryArray)-1;
        if(is_int($whereKey)){ // Logical Operators
          switch ($whereValue) {
            case 'OR':
              $queryArray[$lastKeyQueryArray] = 'OR';
            break;
            case 'AND':
              $queryArray[$lastKeyQueryArray] = 'AND';
            break;
          }
        }else{
          switch ($whereKey) {
            // SQL > ORDER BY
            case 'ORDER':
              if(in_array(@$queryArray[$lastKeyQueryArray],['WHERE','AND','OR'])) unset($queryArray[$lastKeyQueryArray]);
              
              if(is_array($whereValue)){
                $order = array_map(function($n){
                  $m = explode(' ',$n); 
                  return (count($m)==2) ? $this->backtick($m[0]) . ' ' . strtoupper($m[1]) : $this->backtick($m[0]);
                },array_values($whereValue));
                $whereValue = implode(',',$order);
                unset($order,$m);
              }
              $queryArray[] = 'ORDER BY ' . $whereValue;
            break;
            // SQL > LIMIT
            case 'LIMIT':
              if(in_array(@$queryArray[$lastKeyQueryArray],['WHERE','AND','OR'])) unset($queryArray[$lastKeyQueryArray]);
              $queryArray[] = 'LIMIT ' . $whereValue;
            break;
            // SQL > LIKE and NOTLIKE
            case 'LIKE':
            case '!LIKE':
              $not = ( substr($whereKey,0,1) == '!' ) ? 'NOT ' : '';
              $column = key($whereValue);
              $queryArray[] = $this->backtick( $column ) . ' ' . $not . 'LIKE \'' . $whereValue[$column] . "'";
              $queryArray[] = 'AND';
              unset($not);
              unset($column);
            break;
            // SQL > IN and NOT IN
            case 'IN':
            case '!IN':
              $not = ( substr($whereKey,0,1) == '!' ) ? 'NOT ' : '';
              
              $in = array_map(function($n){
                return is_string($n) ? "'" . $n . "'" : $n;
              },array_values($whereValue)[0]);

              $queryArray[] = $this->backtick( key($whereValue) ) . ' ' . $not .  'IN (' . implode(',',$in) . ')';
              $queryArray[] = 'AND';
              unset($not);
              unset($in);
            break;
            // SQL > BETWEEN and  NOT BETWEEN
            case '!BETWEEN':
            case 'BETWEEN':
              $not = ( substr($whereKey,0,1) == '!' ) ? 'NOT ' : '';
              $between = array_values($whereValue)[0];
              if(is_string($between[0]) && is_string($between[1])){
                $between[0] = "'" . $between[0] . "'";
                $between[1] = "'" . $between[1] . "'";
              }
              $queryArray[] =  $this->backtick( key($whereValue) ) . ' ' . $not . 'BETWEEN ' . $between[0] .  ' AND ' .  $between[1];
              $queryArray[] = 'AND';
              unset($not);
              unset($between);
            break;

            default:
              $operator = '=';
              if(preg_match($pattern_comparison_operators, $whereKey, $match_operator) === 1){
                $whereKey = preg_replace($pattern_comparison_operators, "", $whereKey);
                $operator = $match_operator[1];
                unset($match_operator);
              }
              $queryArray[] = $this->backtick( $whereKey ) . $operator . '?';
              $queryArray[] = 'AND';
            break;
          } 
        }
      }
      if(in_array(end($queryArray),['AND','WHERE'])) array_pop($queryArray);
    }
    return isset($queryArray) ? implode(' ',$queryArray) : $where . ';';
  }

  /**
  * Add backtick
  *
  * @param string $str
  * @return string
  */
  private function backtick(string $str){
    $str = explode('.', $str);
    if(count($str) > 1){
      return join('.', array_map(function($n){ return '`'. $n .'`'; },$str));
    }else{
      return '`'. $str[0] .'`';
    }
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
      case is_string($v):   $a = \PDO::PARAM_STR;  break;
      case is_int($v):      $a = \PDO::PARAM_INT;  break;
      case is_bool($v):     $a = \PDO::PARAM_BOOL; break;
      case is_null($v):     $a = \PDO::PARAM_NULL; break;
    }
    return $a ?? \PDO::PARAM_NULL;
  }

  /**
  * Build string DSN
  *
  * @param array $d
  * @return void
  */
  private function buildDSN(array $dsn){
    $this->driver = current( $dsn );
    if(count($dsn) == 2 && is_int(array_search(end($dsn),$dsn))){
      $this->dsn = $dsn[0] . ':' . $dsn[1];
    }else{
      $dsa = [];
      array_shift( $dsn );
      $this->dsn = $this->driver . ':';
      foreach ($dsn as $k => $v) array_push($dsa, $k.'='.$v);
      $this->dsn .= join($dsa,';');
    }
  }

  /**
  * Error exception
  *
  * @param array $data
  * @return void
  */
  private function err_exception(array $data=[]){
    $err = (count($data) != 0) ? $data : $this->errorInfo();
    $err[3] = __CLASS__;
    $err[4] = ($_SERVER['REQUEST_METHOD']=='GET') ? "<html><head></head><body style=\"margin:0;padding:0;\"><div style=\"width:100%;background-color:#660000;padding:15px 0;margin:0;font-family: Century Gothic, sans-serif;text-align:center;color:white;position:absolute;\"><b>{$err[3]} Error</b><br><br>{$err[2]}<br><br><small>{$err[0]} | {$err[1]}</small></div></body></html>" : $err[1];
    exit($err[4]);
  }
}
