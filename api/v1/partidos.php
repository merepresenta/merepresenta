<?php
  require_once realpath(dirname(__FILE__)."/../../lib/leitordados.php");
  require_once realpath(dirname(__FILE__)."/../../lib/saidadados.php");

  $leitor = new LeitorDados("select sigla as sigla_partido from Partido order by 1");

  SaidaDadosFactory::json()->
    exporta( $leitor->leDados() );
?>