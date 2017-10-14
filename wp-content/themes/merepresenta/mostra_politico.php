<?php 
$cand_id = $_GET['cand_id'];

global $wpdb;

$respostas = $wpdb->get_results("select p.texto , r.resposta from Pergunta p join Candidatura c on ( c.id = $cand_id ) left join Resposta r on (r.pessoa_id=c.pessoa_id) and (r.pergunta_id = p.id) order by p.id");

$politico = $wpdb->get_results("select * from politico where candidatura_id=$cand_id")[0];

?>

<main>
  <div class="container">
    <div class="flex">
      <div class="info-basica">
        <?php if (isset($politico->fb_id)) { ?>
          <img class="can-pic" src="http://graph.facebook.com/v2.6/<?= $politico->fb_id ?>/picture?type=large" alt="Foto da/o vereador(a)">
        <?php } else { ?>
          <img class="can-pic" src="https://okeducationtruths.files.wordpress.com/2016/09/not-pictured.png" alt="Foto da/o vereador(a)">
        <?php } ?>
        <span class="info-text name">
          <?= $politico->nome_urna ?>
        </span>
        <span class="info-text party">
          Partido: <?= $politico->sigla_partido ?>
        </span>
        <span class="info-text score">
          Nota do Partido: <?= $politico->nota_partido ?>
        </span>
        <span class="info-text city">
          Cidade: <?= $politico->cidade_eleicao ?>, <?= $politico->uf_eleicao ?>
        </span>
      </div>
      <div class="info-bio">
        <div class="bio">
          <h3>Sobre a/o vereador(a)</h3>
          <textarea readonly rows="11" cols="40"><?= $politico->minibio ?></textarea>
        </div>
      </div>
    </div>

    <div class="respostas">
      <h2>Veja o que a/o vereador(a) respondeu:</h2>
      <table>
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
  </div>
</main>