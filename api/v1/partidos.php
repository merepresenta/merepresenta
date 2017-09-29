<?php
  include "../../lib/leitordados.php";
  include "../../lib/saidadados.php";

  $leitor = new LeitorDados("select sigla as sigla_partido from Partido order by 1");

  SaidaDadosFactory::json()->
    exporta( $leitor->leDados() );
?>