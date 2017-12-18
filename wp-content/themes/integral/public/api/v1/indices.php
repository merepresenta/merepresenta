<?php
  require_once realpath(dirname(__FILE__)."/../../../ambiente.php");

  $ambiente = new Ambiente();
  $ambiente->loadLib("politician_query.php");
  $queryRunner = $ambiente->queryRunner();
  $input = json_decode($ambiente->file_get_contents_utf8('php://input'),true);
  $politicianQuery = new PoliticianQuery($input);

  $requestQuery = $politicianQuery->requestQuery();

  $indexQuery = $politicianQuery->generateIndexQuery();
  $ids = $queryRunner->get_results($indexQuery);

  // Dados acessórios
  $sqlPartidos = $politicianQuery->generateDistinctFieldQuery('sigla_partido as sigla, id_partido as id') . ' order by sigla_partido';
  $sqlEstados = $politicianQuery->generateDistinctFieldQuery('sigla_estado as sigla') . ' order by sigla_estado';
  $sqlGeneros = $politicianQuery->generateDistinctFieldQuery('genero', 'genero <> ""') . ' order by genero';
  $sqlCores = $politicianQuery->generateDistinctFieldQuery('cor_tse', 'cor_tse <> ""') . ' order by cor_tse';
  $sqlSituacoesEleitorais = $politicianQuery->generateDistinctFieldQuery('situacao_eleitoral') . ' order by situacao_eleitoral';

  $maxNumRegistros = intval($input['limites']['quantidade']);

  // Query dos primeiros registros
  $ids = array_map(function($v){ return intval($v->id_candidatura) ;}, $ids);
  $idsString = "";
  $idsArray = [];
  $retorno = [];
  if (sizeof($ids) > 0 ) {
    for($x=0; ($x<$maxNumRegistros) && ($x < sizeof($ids)) ; $x++) {
      $idsString = $idsString . $ids[$x] . ",";
      $idsArray[] = $ids[$x];
    }
    $idsString = substr($idsString,0,strlen($idsString)-1);
    $sql = $politicianQuery->generateUnlimitedQuery("id_candidatura in (${idsString})");
    $dadosForaOrdem = $queryRunner->get_results($sql);    

    $dadosOrdenados = array_map(function($id) use($dadosForaOrdem) {
      $a = array_filter($dadosForaOrdem, function($v) use($id) { 
        return intval($v->id_candidatura) == $id; 
      });
      reset($a);
      return $a[key($a)];
    }, $idsArray );

    $retorno = null;
    $partidos = $queryRunner->get_results($sqlPartidos);
    $estados = $queryRunner->get_results($sqlEstados);
    $generos = $queryRunner->get_results($sqlGeneros);
    $cores = $queryRunner->get_results($sqlCores);
    $situacoesEleitorais = $queryRunner->get_results($sqlSituacoesEleitorais);

    $retorno = $ambiente->empacota2(
      $ids,
      PoliticianQuery::freeUnexportedFields($dadosOrdenados),
      $politicianQuery->input_max_records(),
      $politicianQuery->requestQuery(),
      $partidos,
      $estados,
      $generos,
      $cores,
      $situacoesEleitorais
    );
  }
  else {
    $retorno['query'] = $politicianQuery->requestQuery();
  }

  ($ambiente->exporter(EXP_JSON))->exporta($retorno);
?>
