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

    function insert($table, $data, $format = []) {
      $campos = "";
      $valores = "";
      foreach ($data as $key => $value) {
        $campos .= "$key, ";
        $valores .= "\"$value\",";
      }
      $campos = substr($campos, 0 ,strlen($campos)-1);
      $valores = substr($valores, 0 ,strlen($valores)-1);

      $sql = "insert into $table ($campos) values ($valores)";

      $this->mysqli->query($sql);
      return $this->mysqli->insert_id;
    }
  }
?>