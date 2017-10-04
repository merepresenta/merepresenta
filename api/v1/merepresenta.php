<?php
  require_once realpath(dirname(__FILE__)."/../../lib/leitordados.php");
  require_once realpath(dirname(__FILE__)."/../../lib/saidadados.php");

  $input = json_decode(file_get_contents('php://input'),true);

  $sql = "select * from all_data";
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
    $limits[] = 1;
    $limits[] = 10;
    if ($input['limites']['primeiro']) $limits[0] = $input['limites']['primeiro'];
    if ($input['limites']['ultimo']) $limits[1] = $input['limites']['ultimo'];

  }
  if (sizeof($limits) > 0)
    $sql .= " limit " . join(",", $limits);

  $leitor = new LeitorDados($sql);

  SaidaDadosFactory::peloFormato(array_key_exists ( 'format' , $input ) ? $input['format'] : null)->
    exporta( $leitor->leDados() );
?>



