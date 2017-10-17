<?php
  require_once realpath(dirname(__FILE__)."/../../../ambiente.php");

  $ambiente = new Ambiente();

  $queryRunner = $ambiente->queryRunner();
  $nome = $_GET['nome'];
  $sql = "select c.id, c.nome as nome_cidade, e.sigla as uf from Cidade c inner join Estado e on (e.id = c.estado_id) where c.nome like '%$nome%'";
  $dados = $queryRunner->get_results("$sql order by 1");

  ($ambiente->exporter(EXP_JSON))->
    exporta( $dados );
?>