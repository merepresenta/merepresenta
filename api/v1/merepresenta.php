<?php
  include "../../lib/leitordados.php";
  include "../../lib/saidadados.php";

  $input = json_decode(file_get_contents('php://input'),true);

  $sql = "select * from all_data";
  $limits = array();
  $where = array();
  foreach ($input['query'] as $key => $value) {
    $where[] = $key . ' = "' . $value . '"';
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

  SaidaDadosFactory::peloFormato($input['format'])->
    exporta( $leitor->leDados() );
?>



