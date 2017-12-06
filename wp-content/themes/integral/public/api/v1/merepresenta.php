<?php
  require_once realpath(dirname(__FILE__)."/../../../ambiente.php");

  $ambiente = new Ambiente();
  $ambiente->loadLib("politician_query.php");
  $queryRunner = $ambiente->queryRunner();

  $politicianQuery = new PoliticianQuery(json_decode($ambiente->file_get_contents_utf8('php://input'),true));

  $sql = $politicianQuery->generateQuery();
  $sqlCount = $politicianQuery->generateCountQuery();
  $sqlPartidos = $politicianQuery->generateDistinctFieldQuery('sigla_partido as sigla, id_partido as id') . ' order by sigla_partido';
  $sqlEstados = $politicianQuery->generateDistinctFieldQuery('sigla_estado as sigla') . ' order by sigla_estado';
  $sqlGeneros = $politicianQuery->generateDistinctFieldQuery('genero') . ' where genero <> "" order by genero';
  $sqlCores = $politicianQuery->generateDistinctFieldQuery('cor_tse') . ' where cor_tse <> "" order by cor_tse';
  $sqlSituacoesEleitorais = $politicianQuery->generateDistinctFieldQuery('situacao_eleitoral') . ' order by situacao_eleitoral';

  error_log($sqlCores);

  $dados = $queryRunner->get_results($sql);
  $qtde = ($queryRunner->get_results($sqlCount))[0]->contagem;
  $retorno = null;
  if ($politicianQuery->hasFiltersRequest()) {
    $partidos = $queryRunner->get_results($sqlPartidos);
    $estados = $queryRunner->get_results($sqlEstados);
    $generos = $queryRunner->get_results($sqlGeneros);
    $cores = $queryRunner->get_results($sqlCores);
    $situacoesEleitorais = $queryRunner->get_results($sqlSituacoesEleitorais);
    $retorno = $ambiente->empacota(
      PoliticianQuery::freeUnexportedFields($dados),
      $qtde,
      $politicianQuery->input_first_record(),
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
    $retorno = $ambiente->empacota(
      PoliticianQuery::freeUnexportedFields($dados),
      $qtde,
      $politicianQuery->input_first_record(),
      $politicianQuery->input_max_records()
    );
  }

  ($ambiente->exporter(EXP_JSON))->exporta($retorno);
?>
