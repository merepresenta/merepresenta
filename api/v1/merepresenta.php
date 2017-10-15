<?php
  require_once realpath(dirname(__FILE__)."/../../lib/leitordados.php");
  require_once realpath(dirname(__FILE__)."/../../lib/saidadados.php");

  $input = json_decode(file_get_contents('php://input'),true);

  $sql = "from all_data";
  $sqlLimite = "";

  $limits = array();
  $where = array();
  foreach ($input['query'] as $key => $value) {
    if (($key == 'sigla_estado')||($key == 'sigla_partido')) {
      $values = array_map(function($dado){return '"'.$dado.'"';}, explode(",", $value));
      $where[] = $key . ' in (' . implode(",",$values) . ')';
    } else if ($key == 'nota_partido') {
      $values = explode(",", $value);
      $where[] = $key . " between $values[0] and $values[1] ";
    } else
      $where[] = $key . ' like "%' . $value . '%"';
  }

  if (sizeof($where)>0) {
    $sql = $sql . " where " . join(" and ", $where);
  }

  if ($input['limites']) {
    $limits[] = 0;
    $limits[] = 10;
    if ($input['limites']['primeiro']) $limits[0] = $input['limites']['primeiro'];
    if ($input['limites']['quantidade']) $limits[1] = $input['limites']['quantidade'];

  }
  if (sizeof($limits) > 0)
    $sqlLimite = " limit " . join(",", $limits);

  $leitor = new LeitorDados("select * $sql $sqlLimite", "select count(*) as contagem $sql", $limits[0], $limits[1]);

  SaidaDadosFactory::peloFormato(array_key_exists ( 'format' , $input ) ? $input['format'] : null)->
    exporta( $leitor->leDados(['id_cidade', 'id_partido', 'id_estado']) );
?>



