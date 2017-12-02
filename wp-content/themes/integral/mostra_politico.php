<?php
require_once("ambiente.php");
$ambiente = new Ambiente();
$queryRunner = $ambiente->queryRunner();
$cand_id = $_GET['cand_id'];
$r = $queryRunner->get_results("select * from politico where candidatura_id=$cand_id");
?>
<main>
  <div class="container">
    <?php
    if (sizeof($r) > 0) {
      $respostas = $queryRunner->get_results("select p.texto , r.resposta from Pergunta p join Candidatura c on ( c.id = $cand_id ) left join Resposta r on (r.pessoa_id=c.pessoa_id) and (r.pergunta_id = p.id) order by p.id");
      $politico = $r[0];
    ?>
    <div class="flex">
      <div class="info-basica">
        <div class="row">
          <div class="col-xs-12 col-md-4">
            <?php if (isset($politico->fb_id)) { ?>
            <img class="can-pic img-responsive img-circle" src="//graph.facebook.com/v2.6/<?= $politico->fb_id ?>/picture?type=large" alt="<?= $politico->nome_urna ?>">
            <?php } else { ?>
            <img class="can-pic img-responsive img-circle" src="/wp-content/themes/integral/images/default-profile.jpg" alt="<?= $politico->nome_urna ?>">
            <?php } ?>
          </div>
          <div class="col-xs-12 col-md-4">
            <ul class="list-unstyled">
              <li><b>Nome:</b> <?= $politico->nome_urna ?></li>
              <li><b>Partido:</b> <?= $politico->sigla_partido ?></li>
              <li><b>Nota do Partido:</b> <?= $politico->cidade_eleicao ?>, <?= $politico->uf_eleicao ?></li>
            </ul>
          </div>
          <div class="col-xs-12 col-md-4">
            <h3>Sobre a/o política(o)</h3>
            <p><?= $politico->minibio ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="respostas">
      <h2>Veja o que a/o política(o) respondeu:</h2>
      <table class="table table-striped">
        <tbody>
          <?php for($i=0;$i<sizeof($respostas);$i++) {
          $r=$respostas[$i];
          ?>
          <tr>
            <td class="pergunta "><?=$r->texto?></td>
            <td class="resposta "><?=$r->resposta=="S"?"sim":"não"?></td>
          </tr>
          <?php } ?>
          </tr>
        </tbody>
      </table>
    </div>
    <?php
    } else {
    ?>
    <h1>Não existe candidatura correspondente.</h1>
    <?php } ?>
  </div>
</main>