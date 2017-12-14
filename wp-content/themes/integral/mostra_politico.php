<div class="spacer"></div>
<main>
<?php
  require_once("ambiente.php");
  $ambiente = new Ambiente();
  $queryRunner = $ambiente->queryRunner();

  $cand_id = $_GET['cand_id'];

  $r = $queryRunner->get_results("select * from politico where candidatura_id=$cand_id");
  if (sizeof($r) > 0) {
    $respostas = $queryRunner->get_results("select p.texto , r.resposta from Pergunta p join Candidatura c on ( c.id = $cand_id ) left join Resposta r on (r.pessoa_id=c.pessoa_id) and (r.pergunta_id = p.id) order by p.id");
    $politico = $r[0];
?>
  <div class="container">
    <div class="row bio-header">
      <div class="col-xs-12 col-sm-12 col-md-3 col-md-offset-1">
        <?php if (isset($politico->fb_id)) { ?>
          <img class="img-responsive img-circle" src="//graph.facebook.com/v2.6/<?= $politico->fb_id ?>/picture?type=large" alt="<?= $politico->nome_urna ?>">
        <?php } else { ?>
          <img class="img-responsive img-circle" src="/wp-content/themes/integral/images/default-profile.jpg" alt="<?= $politico->nome_urna ?>">
        <?php } ?>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-8 bio-txt">
        <h1><?= $politico->nome_urna ?></h1>
        <h2>Cidade: <?= $politico->cidade_eleicao ?>, <?= $politico->uf_eleicao ?></h2>
        <h2>Votos: <?= $politico->votos_recebidos ?></h2>
        <?php $situacao_eleitoral = strtolower($politico->situacao_eleitoral) ?>
        <h2  class="data_situacao_cadastral">
          <a target="_blank" href="/situacao-eleitoral/">
            <?= ( $situacao_eleitoral == "eleito por qp" ) ? "Eleito por Quociente Partidário" : $situacao_eleitoral ?>
          </a>
        </h2>
        <ul class="list-unstyled list-inline">
          <li><span class="badge badge-default">Partido: <?= $politico->sigla_partido ?></span></li>
        </ul>
      </div>
    </div><!-- row -->
  </div>

  <div class="container bio">
    <div class="row">
      <div class="col-md-12">
        <h3>Sobre:</h3>
        <p><?= $politico->minibio ?></p>
      </div>
    </div>

    <div class="table-responsive respostas">
      <h3>Veja o que ela/ele respondeu.</h3>
      <table>
        <tbody>
          <?php for($i=0;$i<sizeof($respostas);$i++) {
            $r=$respostas[$i];
            ?>
          <tr>
            <td class="pergunta<?=$r->resposta!="S"?" nao":""?>"><?=$r->texto?></td>
            <td class="resposta<?=$r->resposta!="S"?" nao":""?>"><?=$r->resposta=="S"?"sim":"não"?></td>
          </tr>
          <?php } ?>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
<?php } else { ?>
  <div class="container">
    <h1>Não existe candidatura correspondente.</h1>
  </div>
<?php } ?>
</main>
