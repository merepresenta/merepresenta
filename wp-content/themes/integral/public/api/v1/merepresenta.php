<?php
  require_once realpath(dirname(__FILE__)."/../../../ambiente.php");

  function file_get_contents_utf8($fn) {
       $content = file_get_contents($fn);
        return mb_convert_encoding($content, 'UTF-8',
            mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
  }

  $ambiente = new Ambiente();
  $ambiente->loadLib("politician_query.php");
  $queryRunner = $ambiente->queryRunner();

  $politicianQuery = new PoliticianQuery(json_decode(file_get_contents_utf8('php://input'),true));

  $sql = $politicianQuery->generateQuery();
  $sqlCount = $politicianQuery->generateCountQuery();
  $sqlPartidos = $politicianQuery->generateDistinctFieldQuery('sigla_partido as sigla, id_partido as id ');
  $sqlEstados = $politicianQuery->generateDistinctFieldQuery('sigla_estado as sigla');
  $sqlGeneros = $politicianQuery->generateDistinctFieldQuery('genero');
  $sqlCores = $politicianQuery->generateDistinctFieldQuery('cor_tse');
  $sqlSituacoesEleitorais = $politicianQuery->generateDistinctFieldQuery('situacao_eleitoral');

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



