<?php
  require_once realpath(dirname(__FILE__)."/../../../ambiente.php");

  $ambiente = new Ambiente();
  $ambiente->loadLib("politician_query.php");
  $queryRunner = $ambiente->queryRunner();


  $politicianQuery = new PoliticianQuery(json_decode(file_get_contents('php://input'),true));

  $sql = $politicianQuery->generateQuery();
  $sqlCount = $politicianQuery->generateCountQuery();

  $dados = $queryRunner->get_results($sql);
  $qtde = ($queryRunner->get_results($sqlCount))[0]->contagem;

  $retorno = $ambiente->empacota(PoliticianQuery::freeUnexportedFields($dados), $qtde, $politicianQuery->input_first_record(), $politicianQuery->input_max_records());

  ($ambiente->exporter(EXP_JSON))->exporta($retorno);
?>



