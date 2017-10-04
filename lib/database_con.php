<?php
  require_once realpath(dirname(__FILE__)."/../wp-config.php");

  class DatabaseConnFactory {
    public static function getDatabaseConn() {
      $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $mysqli->set_charset(DB_CHARSET);
      return $mysqli;
    }
  }
?>