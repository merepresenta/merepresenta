<?php
  require_once realpath(dirname(__FILE__)."/../../../../wp-config.php");
  
  class QueryRunner {
    private $mysqli;

    function __construct(){
      $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $this->mysqli->set_charset(DB_CHARSET);
    }

    function __destruct() {
      $this->mysqli->close();
    }

    function get_results($sql) {
      $ret = array();
      $result = $this->mysqli->query($sql);
      while ($reg = $result->fetch_object()) {
        $ret[] = $reg;
      }
      $result->close();
      return $ret;
    }
  }
?>