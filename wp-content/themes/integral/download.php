<?php 
  require_once("ambiente.php");

  $ambiente = new Ambiente();
  $ambiente->loadLib("politician_query.php");
  $queryRunner = $ambiente->queryRunner();

  $query = array(
    'sigla_estado' => $_POST['sigla_estado'],
    'id_cidade' => $_POST['id_cidade'],
    'id_partido' => $_POST['id_partido'],
    'pautas' => $_POST['pautas'],
    'genero' => $_POST['genero'],
    'cor_tse' => $_POST['cor_tse'],
    'nota_partido' => $_POST['nota_partido'],
    'situacao_eleitoral' => $_POST['situacao_eleitoral']
  );

  foreach ($query as $key => $value) {
    if ($value == null)
      unset($query[$key]);
    else {
      $v = preg_replace('/\\\\/', '', $query[$key]);
      $query[$key] = json_decode($v);
    }
  }

  $politicianQuery = new PoliticianQuery(array('query' => $query));
  $sql = $politicianQuery->generateQuery();
  $dados = $queryRunner->get_results($sql);

  ($ambiente->exporter(EXP_CSV))->exporta(PoliticianQuery::freeUnexportedFields($dados));
?>
