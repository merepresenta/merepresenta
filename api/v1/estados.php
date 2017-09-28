<?php
  include "../../lib/leitordados.php";
  include "../../lib/saidadados.php";

  $leitor = new LeitorDados("select sigla as sigla_estado from Estado order by 1");

  SaidaDadosFactory::json()->
    exporta( $leitor->leDados() );
?>