<?php
  require_once realpath(dirname(__FILE__)."/../../lib/leitordados.php");
  require_once realpath(dirname(__FILE__)."/../../lib/saidadados.php");

  $estados = $_GET['estados'];
  $sql = "select c.nome as nome_cidade from Cidade c";
  if ($estados)  {
    $array_estados = array_map( function ($item) {return "'$item'";}, explode(',', $estados));
    $sql = "$sql inner join Estado e on (e.id = c.estado_id) where e.sigla in (" . 
        implode(",", $array_estados ) . ")";

  }
  $leitor = new LeitorDados("$sql order by 1");

  SaidaDadosFactory::json()->
    exporta( $leitor->leDados() );
?>