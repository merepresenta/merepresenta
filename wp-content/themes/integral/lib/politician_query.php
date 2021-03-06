<?php
  class PoliticianQuery {
    private $input;

    function __construct($input) {
      $this->input = $input;
    }

    function requestQuery() {
      return $this->input['query'];
    }

    function hasFiltersRequest() {
      return $this->input['revisaoFiltros'];
    }

    function generateIndexQuery() {
      return "select id_candidatura " . $this->genericQuery();      
    }

    function generateCountQuery() {
      return "select count(*) as contagem " . $this->genericQuery();
    }

    function generateDistinctFieldQuery($field_list, $extraWhere = null) {
      return "select distinct $field_list " . $this->genericDistinctQuery($extraWhere);
    }

    function generateUnlimitedQuery($extraWhere = null) {
      return "select * " . $this->genericQuery($extraWhere);
    }

    function generateQuery() {
      $sqlLimite = "";

      $limits = array();

      if ($this->input['limites']) {
        $limits[] = $this->input_first_record();
        $limits[] = $this->input_max_records();
      }

      if (sizeof($limits) > 0)
        $sqlLimite = " limit " . join(",", $limits);

      return "select * " . $this->genericQuery() . " $sqlLimite";
    }

    function input_first_record() {
      $r = $this->input['limites']['primeiro'];
      return  ($r) ? $r : 0;
    }

    function input_max_records() {
      $r = $this->input['limites']['quantidade'];
      return  ($r) ? $r : 10;
    }

    private function genericQuery($extraWhere = null) {
      $sql = "from all_data";

      $where = array();
      if ($extraWhere) $where[] = $extraWhere;

      foreach ($this->input['query'] as $key => $value) {
        if (($key == 'sigla_estado')||($key == 'cor_tse')||($key=='situacao_eleitoral')) {
          $values = array_map(function($dado){return '"'.$dado.'"';}, $value);
          $where[] = "$key in (" . implode(",",$values) . ')';
        } else if(($key == 'id_cidade')||($key == 'id_partido')) {
          $where[] = "$key in (" . implode(",",$value) . ')';
        } else if ($key == 'genero') {
          if (sizeof($value) == 1) $where[] = "$key = \"".$value[0]."\"";
        } else if ($key == 'pautas') {
          $and = array_map(function($id){return "resposta_$id = \"S\"";}, $value);
          $where[] = "(" . implode(" and ", $and) . ")";
        }
      }

      if (sizeof($where)>0) {
        $sql = $sql . " where " . join(" and ", $where);
      }
      return $sql;
    }

    private function genericDistinctQuery($extraWhere = nil) {
      $sql = "from all_data";

      $where = array();
      if ($where) $where[] = $extraWhere;

      foreach ($this->input['query'] as $key => $value) {
        if ($key == 'pautas') {
          $and = array_map(function($id){return "resposta_$id = \"S\"";}, $value);
          $where[] = "(" . implode(" and ", $and) . ")";
        }
      }

      if (sizeof($where)>0) {
        $sql = $sql . " where " . join(" and ", $where);
      }

      return $sql;
    }

    static function freeUnexportedFields($data) {
      return array_map(function($dado){
        unset($dado->id_cidade);
        unset($dado->id_partido);
        unset($dado->id_estado);
        return $dado;
      }, $data);
    }
  }
?>