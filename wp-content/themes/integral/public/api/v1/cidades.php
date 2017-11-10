<?php
  require_once realpath(dirname(__FILE__)."/../../../ambiente.php");

  $ambiente = new Ambiente();

  $queryRunner = $ambiente->queryRunner();
  $nome = $_GET['nome'];
  $pautas = $_GET['pautas'];
  $sql = '';

  if (isset($pautas)) {
    $sqlPautas = array_map(function($id ){return " inner join Resposta r${id} on r${id}.pergunta_id=$id and r${id}.resposta = \"S\" ";},implode(explode(",",$pautas)));
    $sql =  "select distinct 
              cid.id as id_cidade
              , cid.nome as nome_cidade
              , est.sigla as sigla_estado
          from Cidade cid
          left join Estado est
              on est.id = cid.estado_id
          inner join Candidatura c
              on c.unidade_eleitoral_id = cid.id
          inner join Pessoa p
              on c.pessoa_id = p.id
          ${sqlPautas}
          where cid.nome like '%$nome%' 
          order by cid.nome, est.sigla
      ";
  }
  else {
    $sql = "select c.id, c.nome as nome_cidade, e.sigla as uf from Cidade c inner join Estado e on (e.id = c.estado_id) where c.nome like '%$nome%' order by 1";
  }
  $dados = $queryRunner->get_results($sql);

  ($ambiente->exporter(EXP_JSON))->
    exporta( $dados );
?>
