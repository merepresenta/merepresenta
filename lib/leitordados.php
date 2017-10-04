<?php
  require_once realpath(dirname(__FILE__)."/database_con.php");

  class LeitorDados {
    private $mysqli = null;
    private $sql = null;

    function __construct($sql) {
      $this->mysqli = DatabaseConnFactory::getDatabaseConn();
      $this->sql = $sql;
    }

    function __destruct () {
      $this->mysqli->close();
    }

    public function leDados() {
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