<?php
  require_once realpath(dirname(__FILE__)."/../../../ambiente.php");

  $ambiente = new Ambiente();
  $ambiente->loadLib("politician_query.php");
  $queryRunner = $ambiente->queryRunner();
  $input = json_decode($ambiente->file_get_contents_utf8('php://input'),true);
  $politicianQuery = new PoliticianQuery($input);

  $requestQuery = $politicianQuery->requestQuery();

  $ids = $input['ids_politicos'];
  $idsString = implode(',', $ids);

  $sql = $politicianQuery->generateUnlimitedQuery("id_candidatura in (${idsString})");
  $dadosForaOrdem = $queryRunner->get_results($sql);

  $dadosOrdenados = array_map(function($id) use($dadosForaOrdem) {
    $a = array_filter($dadosForaOrdem, function($v) use($id) { 
      return intval($v->id_candidatura) == $id; 
    });
    reset($a);
    return $a[key($a)];
  }, $ids );

  $retorno = array(
    'dados' => $dadosOrdenados,
    'pagina' => $input['pagina']
  );

  ($ambiente->exporter(EXP_JSON))->exporta($retorno);
?>
