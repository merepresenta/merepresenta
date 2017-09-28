<?php
  class DatabaseConnFactory {
    public static function getDatabaseConn() {
      $mysqli = new mysqli("172.17.0.1", "merepresenta", "12345678", "merepresenta");
      $mysqli->set_charset("utf8");
      return $mysqli;
    }
  }
?>