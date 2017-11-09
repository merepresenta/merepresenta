<?php
  define("EXP_JSON", 1);
  define("EXP_CSV", 2);

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
      return "$this->templateURI/public/assets/css/$file";
    }

    function generateJsURI($file) {
      return "$this->templateURI/public/assets/js/$file";
    }

    function queryRunner() {
      global $wpdb;
      if (isset($wpdb))
        return $wpdb;
      $this->loadLib("query_runner.php");
      return new QueryRunner();
    }

    function exporter($type) {
      $this->loadLib("output/saida_dados.php");

      if($type === EXP_CSV)
        return new SaidaDadosCSV();
      else if($type === EXP_JSON)
        return new SaidaDadosJSON();
      return null;
    }

    function empacota($dados, $qtde, $primeiro, $por_pagina, $query = null, $partidos = null, $estados = null, $generos = null, $cores = null, $situacoes_eleitorais = null) {
        $paginacao = [];
        $paginacao['first'] = $primeiro;
        $paginacao['quantity'] = $por_pagina;
        $paginacao['count'] = $qtde;

        $retorno = [];
        $retorno['data'] = $dados;
        $retorno['pagination'] = $paginacao;
        
        if ($query != null) {
          $filtros = [];
          $filtros['partidos'] = $partidos;
          $filtros['estados'] = $estados;
          $filtros['generos'] = $generos;
          $filtros['cores'] = $cores;
          $filtros['situacoes_eleitorais'] = $situacoes_eleitorais;

          $retorno['filter_data'] = $filtros;          
          $retorno['query'] = $query;          
        }

        return $retorno;
    }
  }
?>