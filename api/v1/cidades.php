<?php
  require_once realpath(dirname(__FILE__)."/../../lib/leitordados.php");
  require_once realpath(dirname(__FILE__)."/../../lib/saidadados.php");

  $nome = $_GET['nome'];
  $sql = "select c.id, c.nome as nome_cidade from Cidade c where c.nome like '%$nome%'";
  $leitor = new LeitorDados("$sql order by 1");

  SaidaDadosFactory::json()->
    exporta( $leitor->leDados() );
?>