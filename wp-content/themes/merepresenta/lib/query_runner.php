<?php
  require_once realpath(dirname(__FILE__)."/../../../../wp-config.php");
  
  class QueryRunnerWordpress {
    private $mysqli;

    function __construct(){
      $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $mysqli->set_charset(DB_CHARSET);
      return $mysqli;
    }

    function __destruct() {
      $this->mysqli->close();
    }

    function get_results($sql) {
      $ret = array();
      $result = $this->mysqli->query($this->sql);
      while ($reg = $result->fetch_object()) {
        $ret[] = $reg;
      }
      $result->close();
      return $ret;
    }
  }
?>