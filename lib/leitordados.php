<?php
  class LeitorDados {
    private $mysqli = null;
    private $sql = null;

    function __construct($sql) {
      $this->mysqli = new mysqli("172.17.0.1", "merepresenta", "12345678", "merepresenta");
      $this->mysqli->set_charset("utf8");
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