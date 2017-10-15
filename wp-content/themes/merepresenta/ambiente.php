<?php
  class Ambiente {
    private $templateDir;
   
    function __construct() {
      if (function_exists("get_template_directory")) {
        $this->templateDir = get_template_directory();
        $this->templateURI = get_template_directory_uri();        
      }
      else {
        $this->templateDir = dirname(__FILE__);
      }
    }

    function loadLib($file) {
      require_once "$this->templateDir/lib/$file";
    }

    function generateCssURI($file) {
      return "$this->templateURI/assets/css/$file";
    }

    function generateJsURI($file) {
      return "$this->templateURI/assets/js/$file";
    }

    function queryRunner() {
      global $wpdb;
      if (isset($wpdb))
        return $wpdb;
      $this->loadLib("query_runner.php");
      return new QueryRunner();
    }

  }
?>